/**
 * Applique le style associé à la valeur référencée par l'étiquette theme dans
 *  le local storage.
 */
export function setLocalStorageTheme() {
    if (localStorage.getItem("theme") === "dark") {
        setDarkTheme();
    } else {
        setLightTheme();
    }
}

/**
 * Applique la classe dark-mode et retire light-mode, gère le background-color
 *  du body et de l'html et stocke une valeur significative dans le local storage.
 */
function setDarkTheme() {
    $("#page-container").removeClass("light-mode").addClass("dark-mode");
    $("#dark-light-mode-button").removeClass(["fa-solid", "fa-sun"]).addClass(["fa-regular", "fa-sun"]);
    $("#name").attr("src", "/frontend/img/name-light.png");
    $("#container").addClass("dark-mode");
    $("#feed-container").addClass("dark-mode");
    $("body, html").css("background-color", "rgb(128, 128, 128)");
    $('#login').css('background-image', 'url("/frontend/img/login_dark_logo.png")');
    $('#register').css('background-image', 'url("/frontend/img/register_dark_logo.png")');
}

/**
 * Applique la classe light-mode et retire dark-mode, gère le background-color
 *  du body et de l'html et stocke une valeur significative dans le local storage.
 */
export function setLightTheme() {
    $("#container").removeClass("dark-mode").addClass("light-mode");
    $("#dark-light-mode-button").removeClass(["fa-solid", "fa-sun"]).addClass(["fa-regular", "fa-moon"]);
    $("body, html").css("background-color", "#fefefe");
    $("#name").attr("src", "/frontend/img/name.png");
    $("#global-loader").css("background-color", "var(--beige-color)");
    $(".spinner").css("border-top", "4px solid var(--dark-purple-color)");
}

export function setButtonSwitchTheme() {
    $("#dark-light-mode-button").on("click", function () {
        if ($("#container").hasClass("dark-mode")) {
            setLightTheme();
            localStorage.setItem('theme', 'light');
        } else {
            setDarkTheme();
            localStorage.setItem('theme', 'dark');
        }
    });
}

/**
 * Initialise le comportement du pop-up pour afficher les indications pour
 *  pour compléter l'input d'id password.
 */
export function setHelpValidator() {
    $("#x-password").on("focus", function () {
        $(this).closest(".password-container").addClass("focus");
    });
    $("#x-password").on("blur", function () {
        $(this).closest(".password-container").removeClass("focus");
    });
}