const monthYear = document.getElementById("monthYear");
const calendarDates = document.getElementById("calendarDates");
const prevMonth = document.getElementById("prevMonth");
const nextMonth = document.getElementById("nextMonth");

let currentDate = new Date();

function renderCalendar(date) {
  const year = date.getFullYear();
  const month = date.getMonth();

  const firstDay = new Date(year, month, 1).getDay();
  const lastDate = new Date(year, month + 1, 0).getDate();
  const today = new Date();

  monthYear.textContent = date.toLocaleString("default", {
    month: "long",
    year: "numeric",
  });

  calendarDates.innerHTML = "";

  for (let i = 0; i < firstDay; i++) {
    calendarDates.innerHTML += `<div></div>`;
  }

  for (let day = 1; day <= lastDate; day++) {
    const isToday =
      day === today.getDate() &&
      month === today.getMonth() &&
      year === today.getFullYear();

    calendarDates.innerHTML += `<div class="${
      isToday ? "today" : ""
    }">${day}</div>`;
  }
}

prevMonth.addEventListener("click", () => {
  currentDate.setMonth(currentDate.getMonth() - 1);
  renderCalendar(currentDate);
});

nextMonth.addEventListener("click", () => {
  currentDate.setMonth(currentDate.getMonth() + 1);
  renderCalendar(currentDate);
});

window.addEventListener("load", () => {
  renderCalendar(currentDate);
});
