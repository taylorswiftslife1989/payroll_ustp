const photoInput = document.getElementById("photoInput");
const photoPreview = document.getElementById("photoPreview");

// When image is clicked, trigger file input
photoPreview.addEventListener("click", () => {
  photoInput.click();
});

photoInput.addEventListener("change", (event) => {
  const file = event.target.files[0];
  if (!file) return;

  const reader = new FileReader();

  reader.onload = function (e) {
    const img = new Image();
    img.src = e.target.result;

    img.onload = function () {
      const canvas = document.createElement("canvas");
      const size = Math.min(img.width, img.height);
      const offsetX = (img.width - size) / 2;
      const offsetY = (img.height - size) / 2;

      canvas.width = canvas.height = size;
      const ctx = canvas.getContext("2d");
      ctx.drawImage(img, offsetX, offsetY, size, size, 0, 0, size, size);

      photoPreview.src = canvas.toDataURL("image/jpeg");
    };
  };

  reader.readAsDataURL(file);
});
