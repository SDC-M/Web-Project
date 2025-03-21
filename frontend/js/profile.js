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
 * Tente d'afficher les images de l'utilisateur connecté sur le profile.
 *  En cas d'échec retourne l'erreur correspondante.
 */
async function getPictures() {
    const userId = await getUserId();
    const url = `/user/${userId}/images`;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        const json = await response.json();
        $.each(json, function (index, picture) {
            let $link = $("<a>").attr("href", `/annotations/${userId}/${picture.id}`);
            let $img = $("<img>").attr("src", `/images/${picture.id}`);
            $link.append($img);
            $('#img-container').append($link);
        });
    } catch (error) {
        console.error(error.message);
    }
}

/**
 * @returns Tente de retourner l'id de l'utilisateur connécté.
 *  En cas d'échec retourne l'erreur correspondante.
 */
async function getUserId() {
    const url = "/user/me";
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

/**
 * Tente d'afficher sur le profil le nombre d'images privées et publiques.
 *  En cas d'échec retourne l'erreur correspondante.
 */
async function getNb() {
    const userId = await getUserId();
    const url = `/user/${userId}/images`;
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
        $("#nb-pictures").append($cptr);
        $("#nb-pictures-private").append($cptr_pri);
    } catch (error) {
        console.error(error.message);
    }
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

/**
 * Tente d'afficher le nom d'utilisateur dans l'element d'id username
 *  en cas d'échec renvoie l'erreur associée.
 */
async function setUserUsername() {
    const path = getPathName();
    const userId = getUserId(path);
    const url = "/user/me"
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        $("#username").html(json.username);
    } catch (error) {
        console.error(error.message);
    }
}

/**
 * Applique la classe dark-mode et retire light-mode, gère le background-color
 *  du body et de l'html et stocke une valeur significative dans le local storage.
 */
function setDarkTheme() {
    $("#container").addClass("dark-mode");
    $("body").css("background-color", "rgb(128, 128, 128)");
    $("html").css("background-color", "rgb(128, 128, 128)");
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(function () {
    if (localStorage.getItem('theme') === 'dark') {
        setDarkTheme();
    }

    getPictures();
    getNb();
    setUserUsername();
});