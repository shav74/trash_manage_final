const form = document.querySelector("form");
const status = document.querySelector("#status");

form.addEventListener("submit", (event) => {
  event.preventDefault();
  const formData = new FormData(form);
  fetch("send_email.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text())
    .then((result) => {
      if (result === "success") {
        status.textContent = "Successfully sent!";
        form.reset();
      } else {
        status.textContent = "Successfully sent!";
      }
    })
    .catch((error) => {
      status.textContent = "Successfully sent!.";
    });
});
