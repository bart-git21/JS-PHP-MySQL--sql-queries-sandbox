<select class="custom-select" id="userSelect">
    <option selected disabled value="0">Пользователь</option>
    <option value="1">Admin</option>
    <option value="2">Item1</option>
    <option value="3">Item2</option>
</select>

<script>
    $(document).ready(function () {
        const user = localStorage.getItem('user') || 0;
        $("#userSelect").val(user);
        $("#userSelect").on("change", function () {
            $.ajax({
                url: "/server/login.php",
                method: "POST",
                data: JSON.stringify({ userId: this.value, }),
                headers: { contentType: "application/json" },
            })
                .done(response => {
                    console.log(response);
                    localStorage.setItem("user", $("#userSelect").val());
                    location.reload();
                })
                .fail((xhr, status, err) => { console.error("Error: ", err) })
                .always()
        })
    })
</script>