/**
 * Applique la classe dark-mode et retire light-mode, gère le background-color
 *  du body et de l'html et stocke une valeur significative dans le local storage.
 */
function setDarkTheme() {
    $("#container").addClass("dark-mode");
    $("body").css("background-color", "rgb(128, 128, 128)");
    $("html").css("background-color", "rgb(128, 128, 128)");
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(function () {
    if (localStorage.getItem('theme') === 'dark') {
        setDarkTheme();
    }
});