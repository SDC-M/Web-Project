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
        const canvas = $("#canvas")[0];
        const ctx = canvas.getContext("2d");
        const json = await response.json();
        $.each(json, async function (index, annotation) {
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

    } catch (error) {
        console.error(error.message);
    }
}

/**
 * @param annotation 
 * Efface le canvas et redessine l'annotation prise en paramètre.
 */
function focusAnnotation(annotation) {
    const canvas = $("#canvas")[0];
    const ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);

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

/**
 * 
 * @returns En cas de succès renvoie le pseudo de l'utilisateur stockée dans l'url
 *  Sinon l'erreur correspondante.
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
 * Affecte la bonne route pour acceder au formulaire d'une nouvelle annotation.
 */
function setNav() {
    const path = getPathName();
    const userId = getUserId(path);
    const imageId = getImageId(path);
    $("#goto-ping").attr("href", `/new_annotations/${userId}/${imageId}`);
}

/**
 * Applique la classe dark-mode et retire light-mode, gère le background-color
 *  du body et de l'html et stocke une valeur significative dans le local storage.
 */
function dark_theme() {
    $("#page-container").removeClass("light-mode").addClass("dark-mode");
    $("body, html").css("background-color", "rgb(128, 128, 128)");
    localStorage.setItem("theme", "dark");
}

function focusDiv(id) {
    $("#annot-content").find("div").each(function () {
        $(this).removeClass("selected");
    });
    $("#" + id).addClass("selected");
}


$(document).ready(function () {
    if (localStorage.getItem("theme") === "dark") {
        dark_theme();
    }

    $(window).resize(function () {
        resizeCanvas();
    });

    displayImage();
    setAnnotations();
    setNav();
});
