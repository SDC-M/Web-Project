async function getPictures() {
    const userId = await getUserId();
    const url = `/image/${userId}`;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        const json = await response.json();
        for (const pictures of json) {
            let img = document.createElement("img");
            img.setAttribute('src', `/image/${userId}/${pictures.id}`);
            let figure = document.createElement("figure");
            let figcaption = document.createElement("figcaption");
            figcaption.setAttribute('id', `${pictures.id}`);
            figcaption.append(`${pictures.description}`);
            figure.appendChild(img);
            figure.appendChild(figcaption);
            document.getElementById('container').appendChild(figure);
        }
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