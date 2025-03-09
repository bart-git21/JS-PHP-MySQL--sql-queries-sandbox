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
    <main class="container-sm">
        <h1 class="text-center">Users queries list</h1>
        <button id="usersQueries">Show</button>
        <div id="usersQueriesList"></div>
    </main>

    <script defer>
        class TableView {
            table() {
                return `
                    <table class="table table-sm table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Пользователь</th>
                                <th scope="col">Название</th>
                                <th scope="col">Текст запроса</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
                    </table>
                `
            }
            row(item) {
                return `
                <tr>
                    <th scope="row">${item.login}</th>
                    <td>${item.name}</td>
                    <td>${item.query}</td>
                </tr>`
            }
            display(data) {
                $("#usersQueriesList").html(this.table());
                data.forEach(e => {
                    $("#tableBody").append(this.row(e))
                })
            }
        }

        $(document).ready(function () {
            $("#usersQueries").on("click", function () {
                $.ajax({
                    url: "../server/users.php",
                    method: "GET",
                })
                    .done(response => {
                        console.log(response);
                        const data = response.map(e => {
                            e.name = decodeURI(encodeURI(e.name));
                            e.query = decodeURI(encodeURI(e.query));
                            return e;
                        })
                        const queriesTable = new TableView();
                        queriesTable.display(data);
                    })
                    .fail((xhr, status, err) => { console.error("Error: ", err) })
                    .always()
            })
        })
    </script>
</body>

</html>