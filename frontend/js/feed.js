import { setLocalStorageTheme } from "./theme.mjs";

/**
 * Tente d'afficher les images de l'utilisateur connecté sur le profile.
 *  En cas d'échec retourne l'erreur correspondante.
 */
async function getPictures() {
  const url = "/api/feed";
  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`Response status: ${response.status}`);
    }

    let json = await response.json();
    $.each(json.image, function (_, picture) {
      let $contain = $("<div>").addClass("img-bloc");
      let $username = $("<p>")
        .html(picture.user.username)
        .css("border-bottom", "solid 1px black")
        .append(" : ");
      let $desc = $("<p>").html(picture.description);
      let $link = $("<a>").attr(
        "href",
        `/annotations/${picture.user.id}/${picture.id}`,
      );
      let $img = $("<img>").attr("src", `/api/image/${picture.id}`);
      $link.append($img);
      $contain.append($link).append($username).append($desc);
      $("#img-container").append($contain);
    });
  } catch (error) {
    console.error(error.message);
  }
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(async function () {
  $("#global-loader").show();
  setLocalStorageTheme();
  await getPictures();
  $("#global-loader").hide();
});
