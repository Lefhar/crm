document.addEventListener("DOMContentLoaded", function() {

    if(document.getElementById("current-state")){
    let currentState = document.getElementById("current-state");
    let selectItems = document.getElementById("select-items").querySelectorAll(".select-item");

    currentState.addEventListener("click", function() {
        console.log("Current state clicked");
        currentState.nextElementSibling.classList.toggle("show");
    });

    selectItems.forEach(function(item) {
        item.addEventListener("click", function() {
            console.log("Option clicked:", this.innerHTML);
            let value = this.getAttribute("data-value");
            currentState.innerHTML = this.innerHTML;
            currentState.style.backgroundColor = this.style.backgroundColor;
            currentState.setAttribute("data-value", value);
            currentState.nextElementSibling.classList.remove("show");
        });
    });

    window.addEventListener("click", function(e) {
        if (!currentState.contains(e.target)) {
            console.log("Window clicked");
            currentState.nextElementSibling.classList.remove("show");
        }
    });
    }
});