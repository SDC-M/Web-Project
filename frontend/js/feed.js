import { setLocalStorageTheme } from "./theme.mjs";
import { setIsAdmin } from "./data-treatment.mjs";

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

let category = "";

/**
 * @param json
 *   Tente de mettre à jour la grille avec les images renvoyés par la requête
 *   passée en paramètre, si le tableau est vide affiche un message sinon
 *   met à jour la page. En cas d'échec renvoie l'erreur correspondante.
 */
function setPictureLoop(json) {
  $("#nfound").html("");
  $.each(json, function (_, picture) {
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
 * Tente d'afficher les images de tous les utilisateurs syr le feed.
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
    setPictureLoop(json.image);
  } catch (error) {
    console.error(error.message);
  }
}

/**
 * Tente d'afficher les images de tous les utilisateurs syr le feed.
 *  Selon la categorie prise en paramètres. En cas d'échec retourne
 *  l'erreur correspondante.
 */
async function getPicturesByCategory(category) {
  const url = `/api/category/${category}/images`;
  try {
    const response = await fetch(url);
    if (!response.ok) {
      if (response.status == 404) {
        if ($("#nfound").length == 0) {
          let $para = $("<p>").attr("id", "nfound").html("No posts yet 😢");
          $("#feed-container").append($para);
        } else {
          $("#nfound").html("No posts yet 😢");
        }
        return;
      }
      throw new Error(`Response status: ${response.status}`);
    }

    let json = await response.json();
    setPictureLoop(json);
  } catch (error) {
    console.error(error.message);
  }
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

/**
 *  Initialise le champ de recherche pour filtrer les photos par catégories.
 *   Lorsqu'une catégorie est sélectionnée, les images correspondantes sont affichées.
 *   Si aucune catégorie n'est sélectionnée, tous les images sont affichées.
 *   En cas d'échec renvoie l'erreur correspondante.
 */
async function setSearchCategories() {
  $("#search").on("change", async function () {
    category = $(this).val();
    $("nfound").html("");
    $("#img-container").empty();
    if (category == "") {
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
  await setIsAdmin();
  $("#global-loader").hide();
});
