import { setLocalStorageTheme } from "./theme.mjs";

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

function setPictureLoop (json){
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
}

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
    setPictureLoop();
  } catch (error) {
    console.error(error.message);
  }
}

async function getPicturesByCategory(category) {
  const url = `/api/category/${category}/images`;
  try {
    const response = await fetch(url);
    if (!response.ok){
      throw new Error(`Response status: ${response.status}`);
    }

    let json = await response.json();
    setPictureLoop();
  } catch (error) {
    console.error(error.message);
  }
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

async function setSearchCategories() {
  $("#search").on("change",async function () {
    category = $(this).val();
    if (category == "all") {
      await getPictures();
    } else {
      await getPicturesByCategory(category);
    }
  });
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(async function () {
  $("#global-loader").show();
  setLocalStorageTheme();
  await getPictures();
  await setSearchCategories();
  $("#global-loader").hide();
});
