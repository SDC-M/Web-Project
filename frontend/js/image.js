function displayImage() {
    const path = document.location.pathname.split('/');
    const userId = path[2];
    const imageId = path[3];

    $('#goto').attr('href', `/new_annotations/${userId}/${imageId}`);

    const imageUrl = `/images/${imageId}`;

    let $img = $("<img>").attr("src", imageUrl).attr("id", "image");
    $('#img-container').html($img);

    $img[0].onload = function () {
        $('#canvas')[0].width = $img[0].clientWidth;
        $('#canvas')[0].height = $img[0].clientHeight;
    };
}

async function getAnnotations() {
    const path = document.location.pathname.split('/');
    const imageId = path[3];
    const url = `/annotation/${imageId}`;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const canvas = $('#canvas')[0];
        const ctx = canvas.getContext('2d');
        const json = await response.json();
        $.each(json, async function (index, annotation) {
            let $userName = await getUsername();
            let $an = $("<p>").html($userName);
            $('#annot-content').append($an)

            let $desc = annotation.description;
            $('#annot-content').append($desc);

            let $Annot1 = annotation.points[0];

            let $Annot2 = annotation.points[1];

            let tab = [$Annot1, $Annot2];
            let tab2 = convertTabPoints(tab, $('#image')[0].naturalWidth, $('#image')[0].clientWidth, $('#image')[0].naturalHeight, $('#image')[0].clientHeight);
            ctx.fillStyle = 'rgba(158, 68, 80, 0.5)';
            ctx.fillRect(Math.min(tab2[0].xCalc, tab2[1].xCalc), Math.min(tab2[0].yCalc, tab2[1].yCalc), Math.abs(tab2[1].xCalc - tab2[0].xCalc), Math.abs(tab2[1].yCalc - tab2[0].yCalc));
        });
    } catch (error) {
        console.error(error.message);
    }
}

function convertPoint(x, y, widthRatio, heightRatio) {
    let xCalc = Math.round(x * widthRatio);
    let yCalc = Math.round(y * heightRatio);

    return { xCalc, yCalc };
}

function convertTabPoints(tab, realWidth, printWidth, realHeight, printHeight) {
    widthRatio = Math.min(realWidth, printWidth) / Math.max(realWidth, printWidth);
    heightRatio = Math.min(realHeight, printHeight) / Math.max(realHeight, printHeight);
    let tab2 = [];
    tab.forEach(element => {
        tab2.push(convertPoint(element.x, element.y, widthRatio, heightRatio));
    });
    return tab2;
}

async function getUsername() {
    const path = document.location.pathname.split('/');
    const userId = path[2];
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

function resizeCanvas() {
    $('#canvas')[0].width = $('#image')[0].clientWidth;
    $('#canvas')[0].height = $('#image')[0].clientHeight;
}

$(document).ready(function () {
    $(window).resize(function () {
        resizeCanvas();
    });

    displayImage();
    getAnnotations();
});