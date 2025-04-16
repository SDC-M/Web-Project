import { onSubmit } from "/frontend/js/form.mjs";

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

/**
 * Redirige l'utilisateur vers la page d'accueil si le formulaire est
 *  correctement rempli.
 */
function redirect() {
  const path = window.location.pathname;
  console.log(path);

  if (path.startsWith("/new_annotations")) {
    window.location.replace(path.replace("new_", ""));
    return;
  }

  const redirection = {
    "/login": "/",
    "/signup": "/",
    "/upload-file": "/profile",
    "/recovery": "/login",
  };

  for (let key in redirection) {
    if (path.startsWith(key)) {
      window.location.replace(redirection[key]);
      return;
    }
  }

  window.location.replace("/");
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(function () {
  $("form").each(function () {
    const $form = $(this);
    onSubmit($form, async function (formData) {
      const req = await fetch($form.attr("action"), {
        method: "POST",
        body: formData,
      });
      const $error = $("#error").css("padding", "0.25rem");
      if (req.ok) {
        $error.html("ok").css("background-color", "green");
        redirect();
      } else {
        $error.html("incorrect");
      }
    });
  });
});
