<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Консоль SQL запросов</title>

    <!-- bootstrap -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <?php include "layouts/header.php" ?>
    </header>
    <main class="container">
        <h1 class="text-center">Users queries list</h1>
        <button id="usersQueries">Show</button>
        <div id="usersQueriesList"></div>
    </main>

    <script defer>
        class TableModel {
            constructor(list) {
                this.list = list;
            }
        }
        class TableView {
            table([...args]) {
                let html = "";
                for (let col of args) {
                    html += `<th scope="col">${col}</th>`
                }
                return `
                    <table class="table table-sm table-hover table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                ${html}
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
                    </table>
                `
            }
            row(object) {
                let html = "";
                for (let key in object) {
                    (key === "login" || key === "id")
                        ? html += `<th scope="row">${object[key]}</th>`
                        : html += `<td>${object[key]}</td>`
                }
                return `
                <tr>
                    ${html}
                </tr>`
            }
            display(initTitles, data) {
                const title = initTitles ? initTitles : Object.keys(data[0]);
                $("#usersQueriesList").html(this.table(title));
                for (let item of data) {
                    const requiredData = initTitles ? Object.assign({}, { "login": item.login, "name": item.name, "query": item.query }) : item;
                    $("#tableBody").append(this.row(requiredData))
                }
            }
        }
        class TableController {
            constructor(view, model) {
                this.view = view;
                this.model = model;
            }
            display(initTitles) {
                this.view.display(initTitles, this.model.list);
            }
        }

        $(document).ready(function () {
            $("#usersQueries").on("click", function () {
                $.ajax({
                    url: "../server/users.php",
                    method: "GET",
                })
                    .done(response => {
                        const data = response.map(e => {
                            e.name = decodeURI(encodeURI(e.name));
                            e.query = decodeURI(encodeURI(e.query));
                            return e;
                        })
                        const queriesTable = new TableController(new TableView(), new TableModel(data));
                        queriesTable.display(["Пользователь", "Название", "Текст запроса"]);
                    })
                    .fail((xhr, status, err) => { console.error("Error: ", err) })
                    .always()
            })
        })
    </script>
</body>

</html>