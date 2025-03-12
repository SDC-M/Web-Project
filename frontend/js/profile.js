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
            const img = document.createElement("img");
            img.setAttribute('src', `/image/${userId}/${pictures.id}`);
            document.getElementById('container').appendChild(img);
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
            throw new Error(`Response status: ${response.status}`);
        }

        const json = await response.json();
        console.log(json.id);
        return json.id;
    } catch (error) {
        console.error(error.message);
    }
}

getPictures();