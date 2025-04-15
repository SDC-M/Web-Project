import { onSubmit } from "/frontend/js/form.mjs";

const form = document.getElementsByTagName("form")[0];
onSubmit(form, async function (formData) {
  let req = await fetch(form.attributes["action"].value, {
    method: "POST",
    body: formData,
  });
  if (req.ok) {
    form.getElementsByClassName("error")[0].innerHTML = "ok";
    window.location.replace("/");
  } else {
    form.getElementsByClassName("error")[0].innerHTML = "Erreur";
  }
});
