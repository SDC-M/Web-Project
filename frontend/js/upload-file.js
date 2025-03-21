/**
 * Initialise le comportement de pré-visionnage de l'image choisie
 *  dans le container associé.
 */
function previewPhoto() {
    const file = $("#file-upload")[0].files;
    if (file && file[0]) {
        const fileReader = new FileReader();
        const preview = $("#file-preview");
        fileReader.onload = function (event) {
            preview.attr("src", event.target.result);
        };
        fileReader.readAsDataURL(file[0]);
    }
}

/**
 * Applique la classe dark-mode et retire light-mode, gère le background-color
 *  du body et de l'html et stocke une valeur significative dans le local storage.
 */
function dark_theme() {
    $("#container").removeClass("light-mode").addClass("dark-mode");
    $("body, html").css("background-color", "rgb(128, 128, 128)");
    localStorage.setItem("theme", "dark");
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(function () {
    if (localStorage.getItem("theme") === "dark") {
        dark_theme();
    }
    $("#file-upload").on("change", previewPhoto);
});
