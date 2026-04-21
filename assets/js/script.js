document.querySelectorAll(".like-btn").forEach(button => {
    button.addEventListener("click", function () {
        let postId = this.getAttribute("data-id");
        let countSpan = document.querySelector(".like-count-" + postId);

        if (!countSpan) return;

        let currentLikes = parseInt(countSpan.innerText);

        fetch("like.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "post_id=" + postId
        })
        .then(res => res.text())
        .then(data => {
            if (data === "liked") {
                countSpan.innerText = currentLikes + 1;
                this.style.color = "red";
            } else if (data === "unliked") {
                countSpan.innerText = currentLikes - 1;
                this.style.color = "black";
            } else if (data === "login_required") {
                window.location.href = "auth/login.php";
            }
        });
    });
});