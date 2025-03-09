<nav class="navbar navbar-expand-sm navbar-light bg-light">
    <span class="navbar-brand mb-0 h1">
        Query sandbox
    </span>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-sm-0">
            <li class="nav-item">
                <a class="nav-link" href="/client/index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/client/pages/query.php">Queries
                    testing</a>
            </li>
        </ul>
    </div>
    <div class="d-flex align-items-center">
        <span class="flex-shrink-0 mr-2">Sign in</span>
            <select class="custom-select" id="userSelect">
                <option selected disabled>Пользователь</option>
                <option value="1">Admin</option>
                <option value="2">Item1</option>
                <option value="3">Item2</option>
            </select>
    </div>
</nav>

<script>
    const links = document.querySelectorAll(".nav-link");
    const id = localStorage.getItem('activePage') || 0;
    (id + 1) && links[id].classList.add("active");
    (id + 1) && links[id].setAttribute("aria-current", "page");

    $(".navbar").on("click", function (event) {
        if (event.target.classList.contains("nav-link")) {
            links.forEach((e, i) => {
                (e === event.target) && localStorage.setItem("activePage", i);
                e.classList.remove("active");
                e.removeAttribute("aria-current");
            })
        }
    })
    $("#userSelect").on("change", function () {
        $.ajax({
            url: "/server/login.php",
            method: "POST",
            data: JSON.stringify({ userId: this.value, }),
            headers: { contentType: "application/json" },
        })
            .done(response => console.log(response))
            .fail((xhr, status, err) => { console.error("Error: ", err) })
            .always()
    })
</script>

<style>
    .nav-item {
        border-bottom: 1px solid transparent;
        transition: all 0.5s;
    }

    .nav-item:hover {
        border-bottom: 1px solid black;
        background-color: #aaa;
    }

    .nav-item:has(a.active) {
        border-bottom: 1px solid black;
    }
</style>