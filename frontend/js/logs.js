const { transformDate } = await import("./data-treatment.mjs");

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

/**
 * @param afterId 
 *  Tente d'afficher les logs à partir de l'indice passé en paramètre dans 
 *   l'ensemble de tous les logs. En cas d'échec renvoie l'erreur 
 *   correspondante.
 */
async function getLogs(afterId) {
    let url = `/api/logs?before=${afterId}`;
    if (afterId == null) {
        url = `/api/logs`;
    }
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        console.log(json);
        $("#tbody").empty();
        $.each(json, function (_, log) {
            let $row = $("<tr>");
            let $id = $("<td>").append(log.id);
            let $desc = $("<td>").append(log.description);
            let $date = $("<td>").append(transformDate(log.creation_date));
            let $owner = $("<td>").append(log.executed_by);
            $row.append($id).append($desc).append($date).append($owner);
            $("#tbody").append($row);
        });
        if (json.length > 0) {
            updatePagination(json[json.length - 1].id);
        }
    } catch (error) {
        console.error(error.message);
    }
}

/**
 * @param afterId
 *  Tente de mettre a jour la pagination avec l'id passé en paramètre, en cas
 *   de succès efface l'ancien tableau, le met à jour puis met à jour l'index
 *   en cas d'échec renvoie l'erreur correspondante.
 */
function updatePagination(afterId) {
    const $pagesContainer = $("#pages");
    $pagesContainer.empty();
    const $nextPage = $("<li>").text("Next");
    $nextPage.on("click", async function () {;
        await getLogs(afterId);
    });
    $pagesContainer.append($nextPage);
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(async function () {
    $("#global-loader").show();
    await getLogs();
    $("#global-loader").hide();
});
