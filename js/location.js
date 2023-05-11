const addLocationBtn = document.querySelector(".add-location-btn");
const dialogBox = document.getElementById("dialogBox");
const dialogOverlay = document.getElementById("dialogOverlay");
const cancelBtn = document.querySelector(".cancel-btn");
const saveBtn = document.querySelector(".save-btn");
const locationInput = document.querySelector(".location-name");
const errorContainer = document.querySelector(".error-container");
const dialogContent = document.querySelector(".dialog-content");

addLocationBtn.addEventListener("click", () => {
  dialogBox.classList.add("show");
  dialogOverlay.style.display = "block";
});

cancelBtn.addEventListener("click", () => {
  dialogBox.classList.remove("show");
  dialogOverlay.style.display = "none";
});

saveBtn.addEventListener("click", () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      async (position) => {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        const locationName = locationInput.value.trim();

        if (locationName === "") {
          errorContainer.textContent = "Name cannot be empty!";
          dialogContent.classList.remove("no-error");
        } else {
          const response = await fetch("save_location.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({ locationName, latitude, longitude }),
          });

          const result = await response.json();

          if (result.success) {
            errorContainer.textContent = "Successfully Added!";
            dialogContent.classList.remove("no-error");
            setTimeout(() => {
              location.reload();
            }, 2000);
          } else {
            errorContainer.textContent = "Error Occured!";
            dialogContent.classList.add("no-error");
          }

          // dialogBox.classList.remove('show');
          // dialogOverlay.style.display = 'none';
        }
      },
      () => {
        errorContainer.textContent = "Switch on your location!";
        dialogContent.classList.add("no-error");
      }
    );
  } else {
    alert("Geolocation is not supported by your browser");
  }
});

function displayAlert(message) {
  return new Promise((resolve) => {
    alert(message);
    resolve();
  });
}
