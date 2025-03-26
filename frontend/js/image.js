const { getPathName, getImageId, getUserId, convertPoint, displayImage, resizeCanvas, setDescription } = await import("./data-treatment.mjs");
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
    let widthRatio = Math.min(realWidth, printWidth) / Math.max(realWidth, printWidth);
    let heightRatio = Math.min(realHeight, printHeight) / Math.max(realHeight, printHeight);
    let tab2 = [];
    tab.forEach(element => {
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
            $("#image")[0].clientHeight);

        ctx.fillStyle = "rgba(158, 68, 80, 0.5)";
        ctx.fillRect(Math.min(tab2[0].xCalc, tab2[1].xCalc),
            Math.min(tab2[0].yCalc, tab2[1].yCalc),
            Math.abs(tab2[1].xCalc - tab2[0].xCalc),
            Math.abs(tab2[1].yCalc - tab2[0].yCalc));
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
    let tab2 = convertTabPoints(tab,
        $("#image")[0].naturalWidth,
        $("#image")[0].clientWidth,
        $("#image")[0].naturalHeight,
        $("#image")[0].clientHeight);
    ctx.fillStyle = "rgba(158, 68, 80, 0.5)";
    ctx.fillRect(Math.min(tab2[0].xCalc, tab2[1].xCalc),
        Math.min(tab2[0].yCalc, tab2[1].yCalc),
        Math.abs(tab2[1].xCalc - tab2[0].xCalc),
        Math.abs(tab2[1].yCalc - tab2[0].yCalc));

    focusDiv(annotation.id);
}

/**
 * Supprime la classe selected de tous les elements si aucun ne 
 *  la possède, sans effet.
 */
function unFocusDiv() {
    $("#annot-content").find("div").each(function () {
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
    const url = `/images/${id}`;
    try {
        const response = await fetch(url, {
            method: 'DELETE',
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
    const url = `/annotation/${id}`;
    try {
        const response = await fetch(url, {
            method: 'DELETE',
        });
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
    } catch (error) {
        console.error(error.message);
    }
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
    const url = `/annotation/${imageId}`;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        const json = await response.json();

        $.each(json, async function (_, annotation) {
            let $desc = annotation.description;
            let $an = $("<p>").html(annotation.user.username).append("</br>").append($desc);
            let $div = $("<div>").attr("class", "comment").attr("id", `${annotation.id}`);
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
            if (await isMindAnnotation(annotation.user.id)) {
                let $del_annotation = $("<div>").addClass("delannotation").html("X");
                $("#annot-content").append($del_annotation);
                $del_annotation.on("click", async function () {
                    const confirmation = window.confirm("Are you sure to delete it ?");
                    if (confirmation) {
                        const userId = await getUserId();
                        await deleteAnnotation(annotation.id);
                        window.location.href = `/annotations/${userId}/${imageId}`;
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
 *  un element pour pouvoir acceder à la suppression de l'image n'affiche rien sinon.
 */
async function setIsMindImage() {
    const path = getPathName();
    const userImg = path[2];
    const userId = await getUserId();
    if (userId == parseInt(userImg)) {
        $("#del").css("display", "block");
    }
}

/**
 * 
 * @param id 
 * @returns Tente de retourner vrai si l'id passé en paramètre correspond à 
 *  l'id de l'utilisateur connecté sinon renvoie faux. En cas d'échec renvoie
 *  l'erreur correspondante. 
 */
async function isMindAnnotation(id) {
    const userId = await getUserId();
    return id == userId;
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(async function () {
    setLocalStorageTheme();
    displayImage();
    const json = await setAnnotations();
    setNav();
    setDeleteImage();
    setDescription();
    setIsMindImage();

    $(window).resize(function () {
        resizeCanvas();
        displayAnnotations(json);
    });
});