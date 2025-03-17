<div id="user" class="mr-2"></div>
<!-- Button trigger modal -->
<div id="loginBtn" type="button" class="btn btn-info btn-sm mr-2" data-toggle="modal" data-target="#usersModal"
    data-title="Sign in">
    Sign in
</div>
<div id="signOnBtn" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#usersModal"
    data-title="Registration">
    Registration
</div>

<!-- Modal -->
<div class="modal fade" id="usersModal" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="usersModalLabel"></h5>
                <button type="button" class="close btn-sm" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="loginForm">
                    <div id="userLogin" class="form-group">
                        <label for="userLoginSelect">Login:</label>
                        <select class="custom-select" id="userLoginSelect"></select>
                        <input id="userLoginInput" class="form-control" style="display:none;" type="text"
                            placeholder="Enter the login">
                    </div>
                    <div class="form-group">
                        <label for="userPassword">Password:</label>
                        <input type="password" class="form-control" id="userPassword" aria-describedby="emailHelp"
                            placeholder="Enter password">
                        <small id="emailHelp" class="form-text text-muted">Enter password.</small>
                    </div>
                    <button id="submitBtn" class="btn btn-primary" type="submit"></button>
                </form>
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
    }
    class UserSelectController {
        constructor(model, view) {
            this.model = model;
            this.view = view;
        }
        create() {
            this.view.createOptions(this.model.list);
        }
    }
    $(document).ready(function () {
        const user = localStorage.getItem('user') || "";
        $("#user").text(user);

        $('#usersModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget); // Button that triggered the modal
            const recipient = button.data('title'); // Extract info from data-* attributes
            if (recipient === "Registration") {
                $("#userLoginSelect").hide();
                $("#userLoginInput").show();
            } else {
                $("#userLoginSelect").show();
                $("#userLoginInput").hide();
            }
            const modal = $(this);
            modal.find('.modal-title').text(recipient);
            modal.find('#submitBtn').text(recipient);
        })

        $("#signOnBtn").on("click", function () {
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
                    $("#user").text(user.login);
                    $("#usersModal").modal("hide");
                })
        })

        // create select option
        $("#loginBtn").on("click", function () {
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

        // changing the select is changing the user
        $("#loginForm").on("submit", function (event) {
            event.preventDefault();
            $.ajax({
                url: "/api/login/",
                method: "POST",
                data: JSON.stringify({
                    id: $("#userLoginSelect").val(),
                    login: $('#userLoginSelect').find('option:selected').text(),
                    password: $("#userPassword").val(),
                }),
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
                    $("#user").text(loggedUser.login);
                    $("#usersModal").modal('hide');
                })
                .fail((xhr, status, err) => { console.error("Error: ", err) })
                .always();
            return false;
        })
    })
</script>