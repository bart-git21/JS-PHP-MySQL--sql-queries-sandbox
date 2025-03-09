<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Консоль SQL запросов</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body>
    <button id="send">Send</button>
    <div id="board">
        <h1>Users queries list</h1>
        <div id="usersQueriesList"></div>
    </div>

    <script defer>
        $(document).ready(function () {
            $("#send").on("click", function () {
                $.ajax({
                    url: "../server/index.php",
                    method: "GET",
                })
                    .done(response => { console.log(response) })
                    .fail((xhr, status, err) => { console.error("Error: ", err) })
                    .always()
            })
        })
    </script>
</body>

</html>