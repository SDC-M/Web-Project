const { getActualUsername, getBiography, setIsAdmin } = await import(
  "./data-treatment.mjs"
);
import { getUserId } from "./data-treatment.mjs";
import { setLocalStorageTheme } from "./theme.mjs";

/**
 * Tente d'afficher les images de l'utilisateur connecté sur le profile.
 *  En cas d'échec retourne l'erreur correspondante.
 */
async function getPictures() {
  const userId = await getUserId();
  const url = `/api/user/${userId}/images`;
  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`Response status: ${response.status}`);
    }

    const json = await response.json();
    $.each(json.image, function (_, picture) {
      let $link = $("<a>").attr("href", `/annotations/${userId}/${picture.id}`);
      let $img = $("<img>")
        .attr("src", `/api/image/${picture.id}`)
        .attr("decoding", "async")
        .attr("loading", "lazy");
      $link.append($img);
      $("#img-container").append($link);
    });
  } catch (error) {
    console.error(error.message);
  }
}

/**
 * Tente d'afficher sur le profil le nombre d'images privées et publiques.
 *  En cas d'échec retourne l'erreur correspondante.
 */
async function getNb() {
  const userId = await getUserId();
  const url = `/api/user/${userId}/images`;
  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`Response status: ${response.status}`);
    }

    const json = await response.json();
    let $cptr = 0;
    let $cptr_pri = 0;
    $.each(json.image, function (index, picture) {
      $cptr += 1;
      if (!picture.is_public) {
        $cptr_pri += 1;
      }
    });
    $("#nb-pictures").append($cptr);
    $("#nb-pictures-private").append($cptr_pri);
  } catch (error) {
    console.error(error.message);
  }
}

/**
 * Tente de mettre dans l'élément d'id nb-likes le nombre de like de l'utilisateur
 *  connecté, en cas d'échec renvoie l'erreur correspondante.
 */
async function setNbLikes() {
  const userId = await getUserId();
  const url = `/api/user/${userId}/likes`;
  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`Response status: ${response.status}`);
    }
    const json = await response.json();
    $("#nb-likes").html(json.nb_likes);
  } catch (error) {
    console.error(error.message);
  }
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

/**
 * Tente d'afficher le nom d'utilisateur dans l'element d'id username
 *  en cas d'échec renvoie l'erreur associée.
 */
async function setUserUsername() {
  const $username = await getActualUsername();
  $("#username").html($username);
}

/**
 * Tente d'afficher la biography de l'utilisateur connecté dans l'element
 *  d'id bio en cas d'échec renvoie l'erreur associé.
 */
async function setBiography() {
  const $biography = await getBiography();
  $("#bio").html($biography);
}

/**
 * Tente d'afficher la photo de profile dans l'image d'if profile-picture,
 *  en cas d'échec renvoie l'erreur correspondante.
 */
async function setProfilePicture() {
  $("#profile-picture").attr("src", `/api/user/${await getUserId()}/picture`);
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(async function () {
  $("#global-loader").show();
  setLocalStorageTheme();
  await getPictures();
  await getNb();
  await setUserUsername();
  await setBiography();
  await setProfilePicture();
  await setNbLikes();
  await setIsAdmin();
  $("#global-loader").hide();
});
