$("#dark-light-mode-button").on("click", function () {
    if ($("#container").hasClass("dark-mode")) {
        $("#container").removeClass("dark-mode");
        $("#container").addClass("light-mode");
        $("#dark-light-mode-button").removeClass(["fa-solid", "fa-sun"]);
        $("#dark-light-mode-button").addClass(["fa-regular", "fa-moon"]);
        $("body").css("background-color", "#f3f2f2");
        $("#name").attr("src", "/frontend/img/name.png");
        $("html").css("background-color", "#fefefe");
        $("body").css("background-color", "#fefefe");
    } else {
        $("#container").removeClass("light-mode");
        $("#container").addClass("dark-mode");
        $("#ldark-light-mode-button").removeClass(["fa-regular", "fa-moon"]);
        $("#dark-light-mode-button").addClass(["fa-solid", "fa-sun"]);
        $("body").css("background-color", "rgb(128, 128, 128)");
        $("html").css("background-color", "rgb(128, 128, 128)");
        $("#name").attr("src", "/frontend/img/name-light.png");
    }
});