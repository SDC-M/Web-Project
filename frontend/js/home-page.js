$(document).ready(function () {
    if (localStorage.getItem('theme') === 'dark') {
        dark_theme();
    } else {
        light_theme();
    }

    $("#dark-light-mode-button").on("click", function () {
        if ($("#container").hasClass("dark-mode")) {
            light_theme();
            localStorage.setItem('theme', 'light');
        } else {
            dark_theme();
            localStorage.setItem('theme', 'dark');
        }
    });
});

function dark_theme() {
    $("#container").removeClass("light-mode").addClass("dark-mode");
    $("#dark-light-mode-button").removeClass(["fa-regular", "fa-moon"]).addClass(["fa-solid", "fa-sun"]);
    $("body").css("background-color", "rgb(128, 128, 128)");
    $("html").css("background-color", "rgb(128, 128, 128)");
    $("#name").attr("src", "/frontend/img/name-light.png");
}

function light_theme() {
    $("#container").removeClass("dark-mode").addClass("light-mode");
    $("#dark-light-mode-button").removeClass(["fa-solid", "fa-sun"]).addClass(["fa-regular", "fa-moon"]);
    $("body").css("background-color", "#f3f2f2");
    $("html").css("background-color", "#fefefe");
    $("body").css("background-color", "#fefefe");
    $("#name").attr("src", "/frontend/img/name.png");
}