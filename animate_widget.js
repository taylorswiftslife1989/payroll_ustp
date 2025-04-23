window.addEventListener("DOMContentLoaded", () => {
  const animatedWidgets = document.querySelectorAll(".animate-on-load");
  animatedWidgets.forEach((widget, index) => {
    setTimeout(() => {
      widget.classList.add("visible");
    }, index * 150); // stagger the animations a bit
  });
});
