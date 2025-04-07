import {
  setHelpValidator,
  setLocalStorageTheme,
  setButtonSwitchTheme,
} from "./theme.mjs";
import { setFileUploadPreview, getActualUsername } from "./data-treatment.mjs";

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

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

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
    $("#biography").val(json.biography);
  } catch (error) {
    console.error(error.message);
  }
}

/**
 * Tente d'afficher dans l'element html d'id p-username le pseudo enregistrer
 *  pour l'utilisateur courrant. En cas d'échec renvoie l'erreur correspondante.
 */
async function setActualUsername() {
  const $username = await getActualUsername();
  $("#p-username").val($username);
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(async function () {
  $("#global-loader").show();
  setNav();
  setFileUploadPreview();
  await setActualBio();
  await setActualUsername();
  setHelpValidator();
  setLocalStorageTheme();
  setButtonSwitchTheme();
  $("#global-loader").hide();
});
