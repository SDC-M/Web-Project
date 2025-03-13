async function getPictures() {
    const userId = await getUserId();
    const url = `/image/${userId}`;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        const json = await response.json();
        $.each(json, function (index, picture) {
            let $img = $("<img>").attr("src", `/image/${userId}/${picture.id}`);
            let $figure = $("<figure>");
            let $figcaption = $("<figcaption>").attr("id", picture.id).text(picture.description);
            $figure.append($img).append($figcaption);
            $('#container').append($figure);
        });
    } catch (error) {
        console.error(error.message);
    }
}

async function getUserId() {
    const url = '/user/me';
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status} `);
        }

        const json = await response.json();
        console.log(json.id);
        return json.id;
    } catch (error) {
        console.error(error.message);
    }
}

function dark_theme() {
    $("body").css("background-color", "rgb(128, 128, 128)");
    $("html").css("background-color", "rgb(128, 128, 128)");
}

$(document).ready(function () {
    if (localStorage.getItem('theme') === 'dark') {
        dark_theme();
    } else {
        $('button').removeClass('dark-button');
    }
});

getPictures();
