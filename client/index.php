<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Консоль SQL запросов</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body>
    <button id="queries">Queries list</button>
    <div id="board">
        <h1>Users queries list</h1>
        <div id="usersQueriesList"></div>
    </div>

    <script defer>
        class TableView {
            constructor(data) {
                this.data = data;
            }
            displayTable() {
                return `
        <table class="table table-sm table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Пользователь</th>
                    <th scope="col">Название</th>
                    <th scope="col">Текст запроса</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">${this.data[0].login}</th>
                    <td>${this.data[0].name}</td>
                    <td>${this.data[0].query}</td>
                </tr>
                <tr>
                    <th scope="row">${this.data[1].login}</th>
                    <td>${this.data[1].name}</td>
                    <td>${this.data[1].query}</td>
                </tr>
                <tr>
                    <th scope="row">${this.data[2].login}</th>
                    <td>${this.data[2].name}</td>
                    <td>${this.data[2].query}</td>
                </tr>
            </tbody>
        </table>
                
                `
            }
        }
        $(document).ready(function () {
            $("#queries").on("click", function () {
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
                        const queriesTable = new TableView(data);
                        $("#usersQueriesList").html(queriesTable.displayTable());
                    })
                    .fail((xhr, status, err) => { console.error("Error: ", err) })
                    .always()
            })
        })
    </script>
</body>

</html>