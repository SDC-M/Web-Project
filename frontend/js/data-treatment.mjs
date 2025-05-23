/**
 * @returns Retourne un tableau composé des élements de l'url,
 *  le séparateur utilisé est '/'.
 */
export function getPathName() {
  return document.location.pathname.split("/");
}

/**
 *
 * @param pathname
 * @returns Retourne l'id de l'image associé.
 */
export function getImageId(pathname) {
  return pathname[3];
}

var me = null;

/**
 * @returns Tente de retourner le json lié à l'utilisateur connecté.
 *  En cas d'échec l'erreur correspondante.
 */
export async function getMe() {
  if (me != null) {
    return me;
  }

  const url = "/api/user/me";
  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`Response status: ${response.status}`);
    }
    const json = await response.json();
    me = json;
    return json;
  } catch (error) {
    console.error(error.message);
  }
}

/**
 *
 * @param pathname
 * @returns Tente de retourner l'id de l'utilisateur connecté.
 *  En cas d'échec l'erreur correspondante.
 */
export async function getUserId() {
  let me = await getMe();
  return me.id;
}

/**
 *
 * @param x
 * @param y
 * @param widthRatio
 * @param heightRatio
 * @returns Un couple de points correspondants aux points pris en
 *  paramètre et mis a l'échelle du container.
 */
export function convertPoint(x, y, widthRatio, heightRatio) {
  let xCalc = Math.round(x * widthRatio);
  let yCalc = Math.round(y * heightRatio);

  return { xCalc, yCalc };
}

/**
 * Affiche l'image sur le canvas de même taille.
 */
export function displayImage() {
  const path = getPathName();
  const imageId = getImageId(path);

  const imageUrl = `/api/image/${imageId}`;

  let $img = $("<img>").attr("src", imageUrl).attr("id", "image");
  $("#img-container").html($img);

  $img[0].onload = function () {
    $("#canvas")[0].width = $img[0].clientWidth;
    $("#canvas")[0].height = $img[0].clientHeight;
  };
}

/**
 * Fixe le canvas sur l'image contenu dans l'element d'id image.
 */
export function resizeCanvas() {
  $("#canvas")[0].width = $("#image")[0].clientWidth;
  $("#canvas")[0].height = $("#image")[0].clientHeight;
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
 * Initialise le comportement lors du changement du fichier dans la section
 *  profile et permet d'afficher le pré-affichage de la photo.
 */
export function setFileUploadPreview() {
  $("#file-upload").on("change", previewPhoto);
}

/**
 * Tente de renvoyer l'username actuel de l'utilisateur connecté.
 *  En cas d'échec renvoie l'erreur correspondante.
 */
export async function getActualUsername() {
  let me = await getMe();
  return me.username;
}

/**
 *
 * @param atomDate
 * @returns Trasforme la date au format atomDate en une date classique.
 */
export function transformDate(atomDate) {
  const date = new Date(atomDate);
  const formattedDate = date.toLocaleString("en-US", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "numeric",
    minute: "numeric",
    second: "numeric",
  });

  return formattedDate;
}

/**
 * Tente d'afficher la description de l'image courrante dans le paragraphe
 *  d'id image-description, en cas d'échec renvoie l'erreur associée.
 */
export async function setDescription() {
  const path = getPathName();
  const imageId = getImageId(path);
  const url = `/api/image/${imageId}/details`;
  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`Response status: ${response.status} `);
    }
    const json = await response.json();
    $("#image-description").append(json.user.username + "<br>");
    if (json.description !== "") {
      $("#image-description").append(json.description + "<br>");
    }
    $("#image-description").append(transformDate(json.creation_date));
  } catch (error) {
    console.error(error.message);
  }
}

/**
 * @returns Tente de retourner la biographie de l'utilisateur connecté,
 *  retourne l'erreur associée en cas d'échec.
 */
export async function getBiography() {
  let me = await getMe();
  return me.biography;
}

/**
 * @param id
 * @returns Tente de renvoyer la visibilité de l'image d'id passé en paramètre
 *  en cas d'échec renvoie l'erreur correspondante.
 */
export async function getVisibilityImage(id) {
  let UrlisVisible = `/api/image/${id}/details`;
  try {
    const response = await fetch(UrlisVisible);
    if (!response.ok) {
      throw new Error(`Response status: ${response.status} `);
    }
    const json = await response.json();
    return json.is_public;
  } catch (error) {
    console.error(error.message);
  }
}

/**
 * @param id
 * Tente de changer la visibilité de l'image d'id passé en paramètre,
 *  en cas d'échec renvoie l'erreur correspondante.
 */
export async function switchVisibilityImage(id) {
  let isVisible = await getVisibilityImage(id);
  const url = `/api/image/${id}/permission`;
  const bodyData = new URLSearchParams();
  bodyData.append("is_public", !isVisible);
  try {
    const response = await fetch(url, {
      method: "PUT",
      body: bodyData,
    });
    if (!response.ok) {
      throw new Error(`Response status: ${response.status}`);
    }
  } catch (error) {
    console.error(error.message);
  }
}

/**
 * @param id
 * @returns Tente de retourner l'id de l'utilisateur à qui appartient
 *  l'image d'id passé en paramètre, en cas d'échec renvoie une erreur.
 */
export async function getOwnerImageId(id) {
  const url = `/api/image/${id}/details`;
  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`Response status: ${response.status} `);
    }
    const json = await response.json();
    return json.user.id;
  } catch (error) {
    console.error(error.message);
  }
}

/**
 * Initialise un bouton pour permettre d'acceder au pannel administrateur
 *  si l'utilisateur en est un. En cas d'échec renvoie l'erreur correspondante.
 */
export async function setIsAdmin() {
  let me = await getMe();
  if (me.is_admin) {
    $("#navbar-list").append(`
      <li>
        <a href="/ad"><i class="fas fa-tools"></i></a>
      </li>
    `);
  }
}
