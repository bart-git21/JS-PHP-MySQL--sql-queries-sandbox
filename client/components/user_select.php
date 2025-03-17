<div id="user" class="mr-2"></div>
<!-- Button trigger modal -->
<div id="loginBtn" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#usersModal">
    Sign in
</div>

<!-- Modal -->
<div class="modal fade" id="usersModal" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="usersModalLabel">Choose the user</h5>
                <button type="button" class="close btn-sm" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <label for="userLoginSelect">Login:</label>
                    <select class="custom-select" id="userLoginSelect">
                        <option selected disabled value="-1">Пользователь</option>
                    </select>
                    <div class="form-group">
                        <label for="userPassword">Password:</label>
                        <input type="password" class="form-control" id="userPassword" aria-describedby="emailHelp"
                            placeholder="Enter password">
                        <small id="emailHelp" class="form-text text-muted">Enter password.</small>
                    </div>
                    <input class="btn btn-primary" type="submit" value="Sign in">
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

        // create select option
        $("#loginBtn").on("click", function () {
            $.ajax({
                url: "/projects/php/php _ sql queries store/api/login/",
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
        $("#userLoginSelect").on("change", function () {
            $.ajax({
                url: "/projects/php/php _ sql queries store/api/login/",
                method: "POST",
                data: JSON.stringify({
                    userId: this.value,
                }),
                headers: { contentType: "application/json" },
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
                .fail((xhr, status, err) => { console.error("Error: ", err) })
                .always()
        })
    })
</script>