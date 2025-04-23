function showPopup(title, message, type) {
  popupTitle.textContent = title;
  popupMessage.textContent = message;
  popup.classList.remove("hidden");

  const iconSrc = type === "success" ? "./assets/ok.png" : "./assets/error.png";
  popupIcon.src = iconSrc;
}
