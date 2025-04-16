import { onSubmit } from "/frontend/js/form.mjs";

function redirect() {
  const path = window.location.pathname;
  console.log(path);

  if (path.startsWith("/new_annotations")) {
    window.location.replace(path.replace("new_", ""));
    return;
  }

  const redirection = {
    "/login": "/aaa",
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
        // As you said, probably we need to change redirect based on the current url
        // window.location.replace("/");
      } else {
        $error.html("incorrect");
      }
    });
  });
});
