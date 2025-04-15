export function onSubmit(form, handler) {
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const data = new FormData(form);
    handler(data);
  });
}

export async function sendPost(url, body, onSuccess, onFailure) {
  let req = await fetch(url, { method: "POST", body: body });
  if (req.ok) {
    form.getElementsByClassName("error")[0].innerHTML = "ok";
    window.location.replace("/");
  } else {
    form.getElementsByClassName("error")[0].innerHTML = "Erreur";
  }
}
