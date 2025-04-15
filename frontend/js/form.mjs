export function onSubmit(form, handler) {
  form.on("submit", function (e) {
    e.preventDefault();
    const data = new FormData(form[0]);
    handler(data);
  });
}
