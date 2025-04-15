import { onSubmit } from "/frontend/js/form.mjs";


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
        window.location.replace("/");
      } else {
        $error.html("incorrect");
      }
    });
  });
});