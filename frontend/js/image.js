/**
 * @returns Retourne un tableau composé des élements de l'url,
 *  le séparateur utilisé est '/'.
 */
function getPathName() {
    return document.location.pathname.split("/");
}

/**
 * 
 * @param pathname 
 * @returns Retourne l'id de l'image associé.
 */
function getImageId(pathname) {
    return pathname[3];
}

/**
 * 
 * @param pathname 
 * @returns Retourne l'id de l'utilisateur associé à l'image.
 */
function getUserId(pathname) {
    return pathname[2];
}

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
 * @param x 
 * @param y 
 * @param widthRatio 
 * @param heightRatio 
 * @returns Un couple de points correspondants aux points pris en
 *  paramètre et mis a l'échelle du container.
 */
function convertPoint(x, y, widthRatio, heightRatio) {
    let xCalc = Math.round(x * widthRatio);
    let yCalc = Math.round(y * heightRatio);

    return { xCalc, yCalc };
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
    widthRatio = Math.min(realWidth, printWidth) / Math.max(realWidth, printWidth);
    heightRatio = Math.min(realHeight, printHeight) / Math.max(realHeight, printHeight);
    let tab2 = [];
    tab.forEach(element => {
        tab2.push(convertPoint(element.x, element.y, widthRatio, heightRatio));
    });
    return tab2;
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

/**
 * Affiche l'image sur le canvas de même taille.
 */
function displayImage() {
    const path = getPathName();
    const imageId = getImageId(path);

    const imageUrl = `/images/${imageId}`;

    let $img = $("<img>").attr("src", imageUrl).attr("id", "image");
    $("#img-container").html($img);

    $img[0].onload = function () {
        $("#canvas")[0].width = $img[0].clientWidth;
        $("#canvas")[0].height = $img[0].clientHeight;
    };
}

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
    ctx.fillStyle = "rgba(158, 68, 80, 0.88)";
    ctx.fillRect(Math.min(tab2[0].xCalc, tab2[1].xCalc),
        Math.min(tab2[0].yCalc, tab2[1].yCalc),
        Math.abs(tab2[1].xCalc - tab2[0].xCalc),
        Math.abs(tab2[1].yCalc - tab2[0].yCalc));

    focusDiv(annotation.id);
}

/**
 * 
 * @returns En cas de succès renvoie le pseudo de l'utilisateur 
 *  stockée dans l'url. Sinon l'erreur correspondante.
 */
async function getUsername() {
    const path = getPathName();
    const userId = getUserId(path);
    const url = `/user/${userId}`;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        return json.username;
    } catch (error) {
        console.error(error.message);
    }
}

/**
 * Fixe le canvas sur l'image contenu dans l'element d'id image.
 */
function resizeCanvas() {
    $("#canvas")[0].width = $("#image")[0].clientWidth;
    $("#canvas")[0].height = $("#image")[0].clientHeight;
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

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

/**
 * Affecte la route pour acceder au formulaire d'une nouvelle annotation
 *  depuis le menu de navigation.
 */
function setNav() {
    const path = getPathName();
    const userId = getUserId(path);
    const imageId = getImageId(path);
    $("#goto-ping").attr("href", `/new_annotations/${userId}/${imageId}`);
}

/**
 * En cas de succès affichage des annotations sur le canvas 
 *  et les informations relatives dans la div de classe comment.
 *  Sinon l'erreur correspondante.
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
            let $userName = await getUsername();
            let $desc = annotation.description;
            let $an = $("<p>").html($userName).append("</br>").append($desc);
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
        });

        displayAnnotations(json);

    } catch (error) {
        console.error(error.message);
    }
}

/**
 * Applique la classe dark-mode et retire light-mode, gère le background-color
 *  du body et de l'html et stocke une valeur significative dans le local storage.
 */
function setDarkTheme() {
    $("#page-container").removeClass("light-mode").addClass("dark-mode");
    $("body, html").css("background-color", "rgb(128, 128, 128)");
    localStorage.setItem("theme", "dark");
}

/**
 * Initialise le comportement du boutton permettant de supprimer une image.
 */
function setDeleteImage() {
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
 * Vérifie que l'image est bien à la personne connecté, dans ce cas affiche
 *  un element pour pouvoir acceder àa ce traitement n'affiche rien sinon.
 */
async function setIsMindImage() {
    const path = getPathName();
    const userId = getUserId(path);
    const url = "/user/me"
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        if (parseInt(json.id) == userId) {
            $("#del").css("display", "block");
        }
    } catch (error) {
        console.error(error.message);
    }
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(function () {
    if (localStorage.getItem("theme") === "dark") {
        setDarkTheme();
    }

    $(window).resize(function () {
        resizeCanvas();
    });

    displayImage();
    setAnnotations();
    setNav();
    setDeleteImage();
    setIsMindImage();
});