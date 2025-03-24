import { setLocalStorageTheme } from "./theme.mjs";

/**
 * Initialise le comportement du pop-up pour afficher les indications pour
 *  pour compléter l'input d'id password.
 */
function setHelpValidator() {
    $("#password").on("focus", function () {
        $(this).closest(".password-container").addClass("focus");
    });
    $("#password").on("blur", function () {
        $(this).closest(".password-container").removeClass("focus");
    });
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(function () {
    setLocalStorageTheme();
    setHelpValidator();
});