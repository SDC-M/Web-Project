import { setLocalStorageTheme } from "./theme.mjs";


/**
 * Tente d'afficher les images de l'utilisateur connecté sur le profile.
 *  En cas d'échec retourne l'erreur correspondante.
 */
async function getPictures() {
    const url = "/feed";
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        let json = await response.json();
        $.each(json.image, function (_, picture) {
            let $link = $("<a>").attr("href", `/annotations/${picture.user.id}/${picture.id}`);
            let $img = $("<img>").attr("src", `/images/${picture.id}`);
            $link.append($img);
            $('#img-container').append($link);
        });
    } catch (error) {
        console.error(error.message);
    }
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(function () {
    setLocalStorageTheme();
    getPictures();
});