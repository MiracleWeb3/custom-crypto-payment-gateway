document.addEventListener("DOMContentLoaded", function() {
    const options = document.querySelectorAll(".network-option");

    options.forEach(option => {
        option.addEventListener("click", function() {
            if (this.classList.contains("selected")) {
                this.classList.remove("selected");
            } else {
                options.forEach(opt => opt.classList.remove("selected"));
                this.classList.add("selected");
            }
        });
    });
});
