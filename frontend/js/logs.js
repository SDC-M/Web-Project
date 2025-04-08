const { transformDate } = await import("./data-treatment.mjs");

async function getLogs(afterId) {
    const url = `/api/logs?after=${afterId}`;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        console.log(json);
        $("#tbody").empty();
        let cptr = 0;
        $.each(json, function (_, log) {
            cptr += 1;
            let $row = $("<tr>");
            let $id = $("<td>").append(log.id);
            let $desc = $("<td>").append(log.description);
            let $date = $("<td>").append(transformDate(log.creation_date));
            let $owner = $("<td>").append(log.executed_by);
            $row.append($id).append($desc).append($date).append($owner);
            $("#tbody").append($row);
        });
        let newAfterId = afterId + cptr;
        updatePagination(newAfterId);
    } catch (error) {
        console.error(error.message);
    }
}

function updatePagination(afterId) {
    const $pagesContainer = $("#pages");
    $pagesContainer.empty();
    const $nextPage = $("<li>").text("Next");
    $nextPage.on("click", async function () {;
        await getLogs(afterId);
    });
    $pagesContainer.append($nextPage);
}

$(document).ready(async function () {
    $("#global-loader").show();
    await getLogs(0);
    $("#global-loader").hide();
});
