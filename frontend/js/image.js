const {
  getPathName,
  getImageId,
  getUserId,
  convertPoint,
  displayImage,
  resizeCanvas,
  setDescription,
  getVisibilityImage,
  switchVisibilityImage,
  getOwnerImageId
} = await import("./data-treatment.mjs");
import { setLocalStorageTheme } from "./theme.mjs";

/**
 * Efface tous les elements du canvas de la page.
 */
function clearCanvas() {
  const canvas = $("#canvas")[0];
  const ctx = canvas.getContext("2d");
  ctx.clearRect(0, 0, canvas.width, canvas.height);
}

/**
 *
 * @param tab
 * @param realWidth
 * @param printWidth
 * @param realHeight
 * @param printHeight
 * @returns Renvoie un tableau de points correspondant aux points du
 *  tableau pris en paramètre mis à l'échelle du container.
 */
function convertTabPoints(tab, realWidth, printWidth, realHeight, printHeight) {
  let widthRatio =
    Math.min(realWidth, printWidth) / Math.max(realWidth, printWidth);
  let heightRatio =
    Math.min(realHeight, printHeight) / Math.max(realHeight, printHeight);
  let tab2 = [];
  tab.forEach((element) => {
    tab2.push(convertPoint(element.x, element.y, widthRatio, heightRatio));
  });
  return tab2;
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

/**
 * @param annotations
 *  Affiche les annotations donner dans le json en paramètre sur le canvas.
 */
async function displayAnnotations(annotations) {
  const canvas = $("#canvas")[0];
  const ctx = canvas.getContext("2d");

  $.each(annotations, async function (_, annotation) {
    let $Annot1 = annotation.points[0];
    let $Annot2 = annotation.points[1];

    let tab = [$Annot1, $Annot2];
    let tab2 = convertTabPoints(
      tab,
      $("#image")[0].naturalWidth,
      $("#image")[0].clientWidth,
      $("#image")[0].naturalHeight,
      $("#image")[0].clientHeight,
    );

    ctx.fillStyle = "rgba(158, 68, 80, 0.5)";
    ctx.fillRect(
      Math.min(tab2[0].xCalc, tab2[1].xCalc),
      Math.min(tab2[0].yCalc, tab2[1].yCalc),
      Math.abs(tab2[1].xCalc - tab2[0].xCalc),
      Math.abs(tab2[1].yCalc - tab2[0].yCalc),
    );
  });
}

/**
 * @param annotation
 * Efface le canvas et redessine l'annotation prise en paramètre.
 *  Affecte le comportement focus à la div associé à la description
 *  de l'annotation.
 */
function focusAnnotation(annotation) {
  const canvas = $("#canvas")[0];
  const ctx = canvas.getContext("2d");
  clearCanvas();

  let $Annot1 = annotation.points[0];
  let $Annot2 = annotation.points[1];

  let tab = [$Annot1, $Annot2];
  let tab2 = convertTabPoints(
    tab,
    $("#image")[0].naturalWidth,
    $("#image")[0].clientWidth,
    $("#image")[0].naturalHeight,
    $("#image")[0].clientHeight,
  );
  ctx.fillStyle = "rgba(158, 68, 80, 0.5)";
  ctx.fillRect(
    Math.min(tab2[0].xCalc, tab2[1].xCalc),
    Math.min(tab2[0].yCalc, tab2[1].yCalc),
    Math.abs(tab2[1].xCalc - tab2[0].xCalc),
    Math.abs(tab2[1].yCalc - tab2[0].yCalc),
  );

  focusDiv(annotation.id);
}

/**
 * Supprime la classe selected de tous les elements si aucun ne
 *  la possède, sans effet.
 */
function unFocusDiv() {
  $("#annot-content")
    .find("div")
    .each(function () {
      $(this).removeClass("selected");
    });
}

/**
 * @param id
 * Attribut la classe selected à la div prise en paramètre.
 */
function focusDiv(id) {
  unFocusDiv();
  $(`#${id}`).addClass("selected");
}

/**
 * @param id
 * Tente de supprimer l'image d'id passée en paramètre, en cas de succès
 *  la supprime sinon renvoie le message d'erreur correspondant.
 */
async function deleteImage(id) {
  const url = `/api/images/${id}`;
  try {
    const response = await fetch(url, {
      method: "DELETE",
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
 *  Tente de supprimer l'annotation d'id passée en paramètre, en cas de succè
 *  la supprime sinon renvoie le message d'erreur correspondant.
 */
async function deleteAnnotation(id) {
  const url = `/api/annotation/${id}`;
  try {
    const response = await fetch(url, {
      method: "DELETE",
    });
    if (!response.ok) {
      throw new Error(`Response status: ${response.status}`);
    }
  } catch (error) {
    console.error(error.message);
  }
}

/**
 * @returns Tente de retourner si l'image d'id stockée à l'indice 3 dans le tableau
 *  lié à l'url est celui de l'utilisateur connecté, dans ce cas retourne vrai, sinon
 *  retourne faux. En cas d'échec renvoie l'erreur correspondante.
 */
async function isMyImage() {
  const path = getPathName();
  const userImg = path[2];
  const userId = await getUserId();
  return userId == parseInt(userImg);
}

/**
 *
 * @param id
 * @returns Tente de retourner vrai si l'id passé en paramètre correspond à
 *  l'id de l'utilisateur connecté sinon renvoie faux. En cas d'échec renvoie
 *  l'erreur correspondante.
 */
async function isMyAnnotation(id) {
  const userId = await getUserId();
  return id == userId;
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

/**
 * Affecte la route pour acceder au formulaire d'une nouvelle annotation
 *  depuis le menu de navigation.
 */
async function setNav() {
  const path = getPathName();
  const userId = await getUserId();
  const imageId = getImageId(path);
  $("#goto-ping").attr("href", `/new_annotations/${userId}/${imageId}`);
}

/**
 * En cas de succès affichage des annotations sur le canvas
 *  et les informations relatives dans la div de classe comment.
 *  et renvoie le json trouvé sinon l'erreur correspondante.
 */
async function setAnnotations() {
  const path = getPathName();
  const imageId = getImageId(path);
  const url = `/api/annotation/${imageId}`;
  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`Response status: ${response.status}`);
    }

    const json = await response.json();

    $.each(json, async function (_, annotation) {
      let $desc = annotation.description;
      let $span = $("<span>")
        .addClass("username")
        .html(annotation.user.username);
      let $an = $("<p>").html($span).append("</br>").append($desc);
      let $div = $("<div>")
        .attr("class", "comment")
        .attr("id", `${annotation.id}`);
      $div.data("annotation", annotation);
      $div.append($an);
      $("#annot-content").append($div);

      $div.on("click", function () {
        const annotation = $(this).data("annotation");
        focusAnnotation(annotation);
      });

      $div.on("dblclick", function () {
        clearCanvas();
        unFocusDiv();
        displayAnnotations(json);
      });
      if ((await isMyAnnotation(annotation.user.id)) || (await isMyImage())) {
        let $del_annotation = $("<div>")
          .addClass("delannotation")
          .html(`<i class="fas fa-trash-alt"></i>`);
        $(`#${annotation.id}`).append($del_annotation);
        $del_annotation.on("click", async function () {
          const confirmation = window.confirm("Are you sure to delete it ?");
          if (confirmation) {
            await deleteAnnotation(annotation.id);
            const ownerIdImage = await getOwnerImageId(imageId);
            window.location.href = `/annotations/${ownerIdImage}/${imageId}`;
          }
        });
      }
    });

    displayAnnotations(json);
    return json;
  } catch (error) {
    console.error(error.message);
  }
}

/**
 * Initialise le comportement du boutton permettant de supprimer une image.
 */
async function setDeleteImage() {
  $("#del").on("click", async function () {
    const imageId = getImageId(getPathName());
    const confirmation = window.confirm("Are you sure to delete it ?");
    if (confirmation) {
      await deleteImage(imageId);
      window.location.href = "/profile";
    }
  });
}

/**
 * Vérifie que l'image est bien à l'utilisateur connecté, dans ce cas affiche
 *  un element pour pouvoir acceder à la suppression de l'image et pour changer
 *  la visibilité de l'image n'affiche rien sinon.
 */
async function setIsMyImage() {
  if (await isMyImage()) {
    setDeleteImage();
    setSwitchVisibilityImage();
    $("#del").css("display", "block");
    $("#privacy").css("display", "block");
  }
}

/**
 * Affiche un element d'id privacy permettant de changer la visibilité d'une image.
 */
async function setSwitchVisibilityImage() {
  let imageId = await getImageId(getPathName());
  if (await getVisibilityImage(getImageId(getPathName()))) {
    $("#privacy").html(`<i class="fas fa-eye"></i>`);
  } else {
    $("#privacy").html(`<i class="fas fa-eye-slash"></i>`);
  }
  $("#privacy").on("click", async function () {
    await switchVisibilityImage(imageId);
  });
}

/**
 * Tente d'afficher dans l'elément d'id likes-cptr le nombre de likes relatifs
 *  à la photo, en cas d'échec renvoie l'erreur correspondante.
 */
async function setCptrLikes() {
  let $imageId = await getImageId(getPathName());
  const url = `/api/image/${$imageId}/likes`;
  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`Response status: ${response.status} `);
    }
    const json = await response.json();
    let $cptr = 0;
    json.forEach((_) => {
      $cptr += 1;
    });
    $("#likes-cptr").html(` ${$cptr}`);
  } catch (error) {
    console.error(error.message);
  }
}

/**
 * Tente d'afficher si l'utilisateur connecté a liké la photo sur laquelle il se trouve,
 *  en cas d'échec renvoie l'erreur correspondante.
 */
async function setIsLiked() {
  let $userId = await getUserId();
  let $imageId = await getImageId(getPathName());
  const url = `/api/image/${$imageId}/likes`;
  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`Response status: ${response.status} `);
    }
    let isin = false;
    const json = await response.json();
    json.forEach((element) => {
      if (element.id == $userId) {
        isin = true;
      }
      if (isin == true) {
        $("#likes-cptr").removeClass("far").addClass("fas");
      }
    });
  } catch (error) {
    console.error(error.message);
  }
}

/**
 * Tente d'initialiser le comportement de l'élément d'id likes-cptr,
 *  si l'utilisateur a déjà liké la photo alors tente d'envoyer une requête
 *  pour enlever le like inversement dans le cas contraire. En cas d'échec
 *  renvoie l'erreur correspondante.
 */
async function setAddAndDeleteLike() {
  let $imageId = await getImageId(getPathName());
  $("#likes-cptr").on("click", async function () {
    if ($("#likes-cptr").hasClass("fas")) {
      const url = `/api/image/${$imageId}/likes`;
      try {
        const response = await fetch(url, {
          method: "DELETE",
        });
        if (!response.ok) {
          throw new Error(`Response status: ${response.status}`);
        }
        $("#likes-cptr").addClass("far").removeClass("fas");
      } catch (error) {
        console.error(error.message);
      }
    } else {
      const url = `/api/image/${$imageId}/likes`;
      try {
        const response = await fetch(url, {
          method: "POST",
        });
        if (!response.ok) {
          throw new Error(`Response status: ${response.status}`);
        }
        $("#likes-cptr").removeClass("far").addClass("fas");
      } catch (error) {
        console.error(error.message);
      }
    }
    setCptrLikes();
  });
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(async function () {
  $("#global-loader").show();
  setLocalStorageTheme();
  displayImage();
  const json = await setAnnotations();
  await setNav();
  await setDescription();
  await setIsMyImage();
  await setCptrLikes();
  await setIsLiked();
  await setAddAndDeleteLike();
  $("#global-loader").hide();
  $(window).resize(async function () {
    resizeCanvas();
    await displayAnnotations(json);
  });
});
