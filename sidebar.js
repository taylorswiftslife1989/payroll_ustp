function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  const toggleArrow = document.getElementById("toggleArrow");
  const systemTitle = document.getElementById("systemTitle");
  const overlay = document.getElementById("overlay");
  const body = document.body;

  sidebar.classList.toggle("closed");

  if (sidebar.classList.contains("closed")) {
    toggleArrow.src = "./assets/right.png"; // Change image to right arrow
    systemTitle.style.display = "none";
    overlay.classList.add("hidden");
    body.classList.remove("body-disabled");
  } else {
    toggleArrow.src = "./assets/left.png"; // Change image to left arrow
    systemTitle.style.display = "block";
    overlay.classList.remove("hidden");
    body.classList.add("body-disabled");
  }
}
