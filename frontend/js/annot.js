function displayImage() {
    const path = document.location.pathname.split('/');
    const userId = path[2];
    const imageId = path[3];
    const imageUrl = `/image/${userId}/${imageId}`;

    let $img = $("<img>").attr("src", imageUrl).attr("id", "image");
    $('#img-container').html($img);

    $img[0].onload = function () {
        $('#canvas')[0].width = $img[0].clientWidth;
        $('#canvas')[0].height = $img[0].clientHeight;
    };
}

function resizeCanvas() {
    $('#canvas')[0].width = $('#image')[0].clientWidth;
    $('#canvas')[0].height = $('#image')[0].clientHeight;
}

function convertPoint(x, y, widthRatio, heightRatio) {
    let xCalc = Math.round(x * widthRatio);
    let yCalc = Math.round(y * heightRatio);

    return { xCalc, yCalc };
}

function convertTabPoints(tab, realWidth, printWidth, realHeight, printHeight) {
    widthRatio = Math.max(realWidth, printWidth) / Math.min(realWidth, printWidth);
    heightRatio = Math.max(realHeight, printHeight) / Math.min(realHeight, printHeight);
    tab2 = [];
    tab.forEach(element => {
        tab2.push(convertPoint(element.x, element.y, widthRatio, heightRatio));
    });
    return tab2;
}

function updateCoords(x1, y1, x2, y2) {
    $('#x1Coord').val(x1);
    $('#y1Coord').val(y1);
    $('#x2Coord').val(x2);
    $('#y2Coord').val(y2);
}

function dark_theme() {
    $("#page-container").removeClass("light-mode").addClass("dark-mode");
    $("body, html").css("background-color", "rgb(128, 128, 128)");
    localStorage.setItem('theme', 'dark');
}

$(document).ready(function () {
    $(window).resize(function () {
        resizeCanvas();
    });

    displayImage();
    const canvas = $('#canvas')[0];
    const ctx = canvas.getContext('2d');
    let points = [];

    $('#canvas').on('mousedown', function (elem) {
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

                ctx.fillStyle = 'rgba(158, 68, 80, 0.5)';
                ctx.fillRect(Math.min(x1, x2), Math.min(y1, y2), Math.abs(x2 - x1), Math.abs(y2 - y1));
                let co = convertTabPoints(points, $('#image')[0].naturalWidth, $('#image')[0].clientWidth, $('#image')[0].naturalHeight, $('#image')[0].clientHeight);
                updateCoords(co[0].xCalc, co[0].yCalc, co[1].xCalc, co[1].yCalc);
            }
        }
    });
    $('#reset').on('click', function () {
        points = [];
        $('#x1Coord').val('');
        $('#y1Coord').val('');
        $('#x2Coord').val('');
        $('#y2Coord').val('');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    });
});

if (localStorage.getItem('theme') === 'dark') {
    dark_theme();
}