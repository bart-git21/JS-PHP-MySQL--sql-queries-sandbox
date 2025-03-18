<div id="user" class="mr-2"></div>
<!-- Button trigger modal -->
<div id="modalBtn" type="button" class="btn btn-info btn-sm mr-2" data-toggle="modal" data-target="#usersModal"
    data-title="Sign in">
    Sign in
</div>
<div id="logoutBtn" type="button" class="btn btn-info btn-sm mr-2" style="display: none;">
    Sign out
</div>

<!-- Modal -->
<div class="modal fade" id="usersModal" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="usersModalLabel">Log in</h5>
                <button type="button" class="close btn-sm" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="loginForm">
                    <div id="userLogin" class="form-group">
                        <label for="userLoginSelect">Login:</label>
                        <select class="custom-select mb-3" id="userLoginSelect"></select>
                        <input id="userLoginInput" class="form-control" type="text" placeholder="Enter the login">
                    </div>
                    <div class="form-group">
                        <label for="userPassword">Password:</label>
                        <input type="password" class="form-control" id="userPassword" aria-describedby="emailHelp"
                            placeholder="Enter password">
                    </div>
                    <div class="form-group">
                        <p><small id="modalError"></small></p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="modalLoginBtn" class="btn btn-primary btn-sm mr-2" type="button">Log in</button>
                <small>New user?</small>
                <button id="modalRegistrationBtn" class="btn btn-outline-primary btn-sm"
                    type="button">Registration</button>
            </div>
        </div>
    </div>
</div>

<script>
    class UserSelectModel {
        constructor(list) {
            // interface List {
            //     id: number;
            //     login: string;
            //     password: string;
            //     role: string;
            // }
            this.list = list;
        }
    }
    class UserSelectView {
        constructor(selector) {
            this.$selector = selector;
        }
        createOptions(list) {
            $(this.$selector).empty();
            $(this.$selector).append(`<option selected disabled value="-1">Пользователь</option>`);
            list.forEach(e => this.addOption(e));
            $(this.$selector).val("-1");
        }
        addOption({ id, login }) {
            $(this.$selector).append(`<option value="${id}">${login}</option>`);
        }
        startOnchangeListener() {
            $(this.$selector).on("change", function () {
                const value = $(this).find("option:selected").text();
                $("#userLoginInput").val(value);
            })
        }
    }
    class UserSelectController {
        constructor(model, view) {
            this.model = model;
            this.view = view;
            this.view.startOnchangeListener();
        }
        create() {
            this.view.createOptions(this.model.list);
        }
    }
    $(document).ready(function () {
        (function () {
            const isLogged = localStorage.getItem('user') ? true : false;
            if (isLogged) {
                $("#logoutBtn").show();
                $("#modalBtn").hide();
                $("#user").text(localStorage.getItem('user') || "");
            }
        })();

        $('#usersModal').on('show.bs.modal', function (e) {
            $("#userLoginInput").val("");
            $("#userPassword").val("");
            $("#modalError").text("");
        })

        // registration logic
        $("#modalRegistrationBtn").on("click", function () {
            $.ajax({
                url: "/api/user/",
                method: "POST",
                data: {
                    login: $("#userLogin").val(),
                    password: $("#userPassword").val(),
                },
                headers: {
                    "Content-Type": "application/json",
                }
            })
                .done(user => {
                    // interface User {
                    //     id: number,
                    //     login: string,
                    // }
                    localStorage.setItem('user', user.login);
                    location.reload();
                })
        })

        // log out logic
        $("#logoutBtn").on("click", function () {
            $.ajax({
                url: "/api/login/",
                method: "DELETE",
            })
                .done(response => {
                    localStorage.removeItem('user');
                    localStorage.removeItem('userId');
                    location.reload();
                })
        })

        // create select option
        $("#modalBtn").on("click", function () {
            $.ajax({
                url: "/api/login/",
                method: "GET",
            })
                .done(users => {
                    // interface Users {
                    //     id: number;
                    //     login: string;
                    //     password: string;
                    //     role: string;
                    // }
                    const userSelect = new UserSelectController(new UserSelectModel(users), new UserSelectView("#userLoginSelect"));
                    userSelect.create();
                })
                .fail((xhr, status, err) => { console.error("Error: ", err) })
                .always()
        })

        // log in
        $("#modalLoginBtn").on("click", function (event) {
            const data = {
                login: $('#userLoginInput').val(),
                password: $("#userPassword").val()
            }
            $.ajax({
                url: "/api/login/",
                method: "POST",
                data: JSON.stringify(data),
                headers: {
                    "Content-Type": "application/json",
                },
            })
                .done(loggedUser => {
                    // interface LoggedUser {
                    //     login: string;
                    //     userId: number;
                    // }
                    localStorage.setItem("user", loggedUser.login);
                    localStorage.setItem("userId", loggedUser.userId);
                    location.reload();
                })
                .fail((xhr, status, err) => {
                    if (xhr.status === 401) {
                        $("#modalError").text("Invalid login or password");
                    }
                })
                .always();
            return false;
        })
    })
</script>