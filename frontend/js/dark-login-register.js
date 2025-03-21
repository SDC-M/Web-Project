/**
 * Applique la classe dark-mode et retire light-mode, gère le background-color
 *  du body et de l'html et stocke une valeur significative dans le local storage.
 */
function dark_theme() {
    $('#login').css('background-image', 'url("/frontend/img/login_dark_logo.png")');
    $('#register').css('background-image', 'url("/frontend/img/register_dark_logo.png")');
    $('button').addClass('dark-button');
    $('button').hover(
        function () {
            $(this).css('background-color', '#69596b');
        }).on('mouseleave', function () {
            $(this).css('background-color', '');
        });
    $("body").css("background-color", "rgb(128, 128, 128)");
    $("html").css("background-color", "rgb(128, 128, 128)");
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(function () {
    if (localStorage.getItem('theme') === 'dark') {
        dark_theme();
    } else {
        $('button').removeClass('dark-button');
    }
});