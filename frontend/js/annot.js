const { getPathName, getImageId, getUserId, convertPoint, displayImage, resizeCanvas } = await import("./data-treatment.mjs");
import { setLocalStorageTheme } from "./theme.mjs";

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
    let widthRatio = Math.max(realWidth, printWidth) / Math.min(realWidth, printWidth);
    let heightRatio = Math.max(realHeight, printHeight) / Math.min(realHeight, printHeight);
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

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

/**
 * Affecte à l'element d'id goto-image la route pour acceder à l'image et 
 *  ses annotations depuis le menu de navigation.
 */
async function setNav() {
    const path = getPathName();
    const userId = await getUserId();
    const imageId = getImageId(path);
    $("#goto-image").attr("href", `/annotations/${userId}/${imageId}`);
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
 * Initialise le comportement du canvas lorsque l'utlisateur selectionne sa zone,
 *  et affecte sa fonction reset au boutton associé.
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

            ctx.fillStyle = "black";
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
    $("#reset").on("click", function () {
        points = [];
        $("#x1Coord").val("");
        $("#y1Coord").val("");
        $("#x2Coord").val("");
        $("#y2Coord").val("");
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    });
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(function () {
    setLocalStorageTheme();
    setFormAction();
    displayImage();
    setCanvas();
    setNav();

    $(window).resize(function () {
        resizeCanvas();
    });
});