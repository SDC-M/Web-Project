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
