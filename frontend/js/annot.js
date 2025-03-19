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
 * Affecte la bonne route pour l'envoi du formulaire.
 */
function setFormAction() {
    const path = getPathName();
    const imageId = getImageId(path);
    $("#annot").attr("action", `/annotation/${imageId}`);
}

/**
 * Initialise le comportement du boutton reset pour effacer le canvas.
 */
function setResetButton() {
    const canvas = $("#canvas")[0];
    const ctx = canvas.getContext("2d");
    $("#reset").on("click", function () {
        points = [];
        $("#x1Coord").val("");
        $("#y1Coord").val("");
        $("#x2Coord").val("");
        $("#y2Coord").val("");
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    });
}

/**
 * Initialise le comportement du canvas lorsque l'utlisateur selectionne sa zone.
 */
function setCanvas() {
    const canvas = $("#canvas")[0];
    const ctx = canvas.getContext("2d");
    let points = [];

    $("#canvas").on("mousedown", function (elem) {
        if (points.length < 2) {
            const x = elem.offsetX;
            const y = elem.offsetY;
            points.push({ x, y });

            ctx.beginPath();
            ctx.arc(x, y, 5, 0, 2 * Math.PI);
            ctx.fill();

            if (points.length === 2) {
                const x1 = points[0].x;
                const y1 = points[0].y;
                const x2 = points[1].x;
                const y2 = points[1].y;

                ctx.fillStyle = "rgba(158, 68, 80, 0.62)";
                ctx.fillRect(Math.min(x1, x2),
                    Math.min(y1, y2),
                    Math.abs(x2 - x1),
                    Math.abs(y2 - y1));
                let co = convertTabPoints(
                    points, $("#image")[0].naturalWidth,
                    $("#image")[0].clientWidth,
                    $("#image")[0].naturalHeight,
                    $("#image")[0].clientHeight);

                updateCoords(co[0].xCalc, co[0].yCalc, co[1].xCalc, co[1].yCalc);
            }
        }
    });
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
 * Fixe le canvas sur l'image contenu dans l'element d'id image.
 */
function resizeCanvas() {
    $("#canvas")[0].width = $("#image")[0].clientWidth;
    $("#canvas")[0].height = $("#image")[0].clientHeight;
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
    widthRatio = Math.max(realWidth, printWidth) / Math.min(realWidth, printWidth);
    heightRatio = Math.max(realHeight, printHeight) / Math.min(realHeight, printHeight);
    let tab2 = [];
    tab.forEach(element => {
        tab2.push(convertPoint(element.x, element.y, widthRatio, heightRatio));
    });
    return tab2;
}

/**
 * 
 * @param x1 
 * @param y1 
 * @param x2 
 * @param y2 
 * Mets à jour les input du formulaire avec les coordonnées prises en paramètres.
 */
function updateCoords(x1, y1, x2, y2) {
    $("#x1Coord").val(x1);
    $("#y1Coord").val(y1);
    $("#x2Coord").val(x2);
    $("#y2Coord").val(y2);
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

$(document).ready(function () {
    if (localStorage.getItem("theme") === "dark") {
        dark_theme();
    }

    setFormAction();
    displayImage();
    setCanvas();

    $(window).resize(function () {
        resizeCanvas();
    });
});