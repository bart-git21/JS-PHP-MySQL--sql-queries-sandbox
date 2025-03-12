<div id="user" class="mr-2"></div>
<!-- Button trigger modal -->
<div id="login" type="button" class="btn btn-info" data-toggle="modal" data-target="#usersModal">
    Sign on
</div>

<!-- Modal -->
<div class="modal fade" id="usersModal" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="usersModalLabel">Choose the user</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <select class="custom-select" id="userSelect">
                    <option selected disabled value="0">Пользователь</option>
                </select>
            </div>
        </div>
    </div>
</div>

<script>
    class UserSelectModel {
        constructor(list) {
            this.list = list;
        }
    }
    class UserSelectView {
        createOption(data) {
            return `<option value="${data.id}">${data.login}</option>`
        }
        setSelect(list, selector) {
            let optionsList = "";
            list.forEach(e => optionsList += this.createOption(e));
            $(`${selector}`).html(optionsList)
        }
    }
    class UserSelectController {
        constructor(model, view) {
            this.model = model;
            this.view = view;
        }
        create(selector) {
            this.view.setSelect(this.model.list, selector);
        }
    }
    $(document).ready(function () {
        const user = localStorage.getItem('user') || "";
        $("#user").text(user);

        // create select option
        $("#login").on("click", function () {
            $.ajax({
                url: "/server/login.php",
                method: "GET",
            })
                .done(response => {
                    console.log(response);
                    const userSelect = new UserSelectController(new UserSelectModel(response), new UserSelectView());
                    userSelect.create("#userSelect");
                })
                .fail((xhr, status, err) => { console.error("Error: ", err) })
                .always()
        })

        // changing the select is changing the user
        $("#userSelect").on("change", function () {
            $.ajax({
                url: "/server/login.php",
                method: "POST",
                data: JSON.stringify({ userId: this.value, }),
                headers: { contentType: "application/json" },
            })
                .done(response => {
                    console.log(response);
                    localStorage.setItem("user", response.login);
                    localStorage.setItem("userId", response.userId);
                    location.reload();
                })
                .fail((xhr, status, err) => { console.error("Error: ", err) })
                .always()
        })
    })
</script>