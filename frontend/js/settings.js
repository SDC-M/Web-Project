/**
 * Permet d'enlever toutes les sections de l'affichage.
 */
function unSelect() {
    $("#nav-list li a").each(function () {
        if ($(this).attr("href") !== "/profile") {
            $(`${$(this).attr("href")}`).css("display", "none");
        }
    });
}

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
    $("#container").addClass("dark-mode");
}

/**
 * Applique la classe light-mode et retire dark-mode, gère le background-color
 *  du body et de l'html et stocke une valeur significative dans le local storage.
 */
function light_theme() {
    $("#container").removeClass("dark-mode")
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

/**
 * Applique la classe dark-mode et retire light-mode, gère le background-color
 *  du body et de l'html et stocke une valeur significative dans le local storage.
 */
function setDarkTheme() {
    $("#container").addClass("dark-mode");
    $("body").css("background-color", "rgb(128, 128, 128)");
    $("html").css("background-color", "rgb(128, 128, 128)");
}

/**
 * Permet d'initialiser le comportement du menu de navigation pour
 *  l'apparition des différentes sections.
 */
function setNav() {
    $("#interface-button").on("click", function () {
        unSelect();
        $("#interface").css("display", "flex");
    });
    $("#profile-button").on("click", function () {
        unSelect();
        $("#profile").css("display", "flex");
    });
    $("#change-password-button").on("click", function () {
        unSelect();
        $("#change-password").css("display", "flex");
    });
    $("#change-email-button").on("click", function () {
        unSelect();
        $("#change-email").css("display", "flex");
    });
}

/**
 * Initialise le comportement de changement de theme modifiant une valeur
 *  indicative dans le local storage.
 */
function setDarkLightButton() {
    $("#switch-mode-button").on("click", function () {
        if ($("#container").hasClass("dark-mode")) {
            light_theme();
            localStorage.setItem('theme', 'light');
        } else {
            dark_theme();
            localStorage.setItem('theme', 'dark');
        }
    });
}

/**
 * Initialise le comportement lors du changement du fichier dans la section
 *  profile et permet d'afficher le pré-affichage de la photo.
 */
function setFileUploadPreview() {
    $("#file-upload").on("change", previewPhoto);
}

/**
 * Tente d'afficher dans l'element html d'id biography la biographie enregistrer
 *  pour l'utilisateur courrant. En cas d'échec renvoie l'erreur correspondante.
 */
async function setActualBio() {
    const url = "/user/me";
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status} `);
        }

        const json = await response.json();
        $("#biography").val(json.bio);
    } catch (error) {
        console.error(error.message);
    }
}

/**
 * Tente d'afficher dans l'element html d'id p-username le pseudo enregistrer
 *  pour l'utilisateur courrant. En cas d'échec renvoie l'erreur correspondante.
 */
async function setActualUsername() {
    const url = "/user/me";
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status} `);
        }

        const json = await response.json();
        console.log(json.username);
        $("#p-username").val(json.username);
    } catch (error) {
        console.error(error.message);
    }
}

/**
 * Initialise le comportement du pop-up pour afficher les indications pour
 *  pour compléter l'input d'id new-password.
 */
function setHelpValidator() {
    $("#new-password").on("focus", function () {
        $(this).closest(".password-container").addClass("focus");
    });
    $("#new-password").on("blur", function () {
        $(this).closest(".password-container").removeClass("focus");
    });
}
/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(function () {
    if (localStorage.getItem('theme') === 'dark') {
        setDarkTheme();
    }
    setNav();
    setDarkLightButton();
    setFileUploadPreview();
    setActualBio();
    setActualUsername();
    setHelpValidator();
});