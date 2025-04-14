const { transformDate } = await import("./data-treatment.mjs");

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

let username = "";

/**
 * @param json
 *  Tente de mettre à jour le tableau avec les logs renvoyés par la requête
 *   passée en paramètre, si le tableau est vide cache la pagination sinon
 *   met à jour la pagination avec le dernier id du tableau.
 *   En cas d'échec renvoie l'erreur correspondante.
 */
function renderLogTable(json) {
  $("#tbody").empty();
  $("#pages-container").css("display", "block");
  $.each(json, function (_, log) {
    let $row = $("<tr>");
    let $id = $("<td>").append(log.id);
    let $desc = $("<td>").append(log.description);
    let $date = $("<td>").append(transformDate(log.creation_date));
    let $owner = $("<td>").append(log.executed_by);
    $row.append($id).append($desc).append($date).append($owner);
    $("#tbody").append($row);
  });
}

/**
 * @param json
 *  Tente de mettre à jour le tableau avec les logs renvoyés par la requête
 *   passée en paramètre, si le tableau est vide cache la pagination sinon
 *   met à jour la pagination avec le dernier id du tableau.
 *   En cas d'échec renvoie l'erreur correspondante.
 */
function renderAndUpdatePagination(json) {
  renderLogTable(json);
  if (json.length > 0) {
    updatePagination(json[json.length - 1].id);
  } else {
    $("#pages-container").css("display", "none");
  }
}

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
    renderAndUpdatePagination(json);
  } catch (error) {
    console.error(error.message);
  }
}

/**
 * @param username
 * @param afterId
 * Tente d'afficher les logs à partir de l'indice passé en paramètre dans
 *  l'ensemble de tous les logs de l'utilisateur passé en paramètre.
 *  En cas d'échec renvoie l'erreur correspondante.
 */
async function getLogsByUsername(username, afterId) {
  let url = `/api/logs?filter=executed_by&executor=${username}&before=${afterId}`;
  if (afterId == null) {
    url = `/api/logs?filter=executed_by&executor=${username}`;
  }
  try {
    const response = await fetch(url);
    if (!response.ok) {
      if (response.status == 404) {
        $("#tbody").html("<tr><td colspan='4'>No logs found</td></tr>");
        $("#pages-container").css("display", "none");
        return;
      }
      throw new Error(`Response status: ${response.status}`);
    }
    const json = await response.json();
    renderAndUpdatePagination(json);
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
  $nextPage.on("click", async function () {
    if (username != "") {
      await getLogsByUsername(username, afterId);
    } else {
      await getLogs(afterId);
    }
  });
  $pagesContainer.append($nextPage);
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

/**
 *  Initialise le champ de recherche pour filtrer les logs par utilisateur.
 *   Lorsqu'un utilisateur est sélectionné, les logs correspondants sont affichés.
 *   Si aucun utilisateur n'est sélectionné, tous les logs sont affichés.
 *   En cas d'échec renvoie l'erreur correspondante.
 */
async function setSearch() {
  $("#username").on("change", async function () {
    username = $(this).val();
    if (username != "") {
      await getLogsByUsername(username);
    } else {
      await getLogs();
    }
  });
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(async function () {
  $("#global-loader").show();
  await getLogs();
  await setSearch();
  $("#global-loader").hide();
});
