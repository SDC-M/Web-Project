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
            $('#img-container').append($img);
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
        return json.id;
    } catch (error) {
        console.error(error.message);
    }
}

async function getNb() {
    const userId = await getUserId();
    const url = `/image/${userId}`;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        const json = await response.json();
        let $cptr = 0;
        let $cptr_pri = 0;
        $.each(json, function (index, picture) {
            $cptr += 1;
            if (!picture.is_public) {
                $cptr_pri += 1;
            }
        });
        $("#affichage-compteur").append($cptr);
        $("#affichage-compteur-prive").append($cptr_pri);
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
getNb();