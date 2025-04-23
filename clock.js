export function drawClock() {
  const canvas = document.getElementById("analogClock");
  const ctx = canvas.getContext("2d");
  const radius = canvas.height / 2;
  ctx.translate(radius, radius);

  function drawFace() {
    ctx.beginPath();
    ctx.arc(0, 0, radius * 0.95, 0, 2 * Math.PI);
    ctx.fillStyle = "#fff";
    ctx.fill();
    ctx.strokeStyle = "#333";
    ctx.lineWidth = radius * 0.05;
    ctx.stroke();
    ctx.beginPath();
    ctx.arc(0, 0, radius * 0.1, 0, 2 * Math.PI);
    ctx.fillStyle = "#333";
    ctx.fill();
  }

  function drawNumbers() {
    ctx.font = radius * 0.15 + "px arial";
    ctx.textBaseline = "middle";
    ctx.textAlign = "center";
    for (let num = 1; num <= 12; num++) {
      let ang = (num * Math.PI) / 6;
      ctx.rotate(ang);
      ctx.translate(0, -radius * 0.8);
      ctx.rotate(-ang);
      ctx.fillText(num.toString(), 0, 0);
      ctx.rotate(ang);
      ctx.translate(0, radius * 0.8);
      ctx.rotate(-ang);
    }
  }

  function drawTime() {
    const now = new Date();
    let hour = now.getHours();
    let minute = now.getMinutes();
    let second = now.getSeconds();
    hour = hour % 12;
    hour =
      (hour * Math.PI) / 6 +
      (minute * Math.PI) / (6 * 60) +
      (second * Math.PI) / (360 * 60);
    drawHand(hour, radius * 0.5, radius * 0.07);
    minute = (minute * Math.PI) / 30 + (second * Math.PI) / (30 * 60);
    drawHand(minute, radius * 0.8, radius * 0.07);
    second = (second * Math.PI) / 30;
    drawHand(second, radius * 0.9, radius * 0.02, "red");
  }

  function drawHand(pos, length, width, color = "#333") {
    ctx.beginPath();
    ctx.lineWidth = width;
    ctx.lineCap = "round";
    ctx.strokeStyle = color;
    ctx.moveTo(0, 0);
    ctx.rotate(pos);
    ctx.lineTo(0, -length);
    ctx.stroke();
    ctx.rotate(-pos);
  }

  function draw() {
    ctx.clearRect(-radius, -radius, canvas.width, canvas.height);
    drawFace();
    drawNumbers();
    drawTime();
  }

  setInterval(draw, 1000);
}

export function updateDigitalClock() {
  const digitalClock = document.getElementById("digitalClock");

  function formatTime(date) {
    let hours = date.getHours();
    let minutes = date.getMinutes();
    const ampm = hours >= 12 ? "PM" : "AM";
    hours = hours % 12 || 12;
    minutes = minutes < 10 ? "0" + minutes : minutes;
    return `${hours}:${minutes} ${ampm}`;
  }

  setInterval(() => {
    digitalClock.textContent = formatTime(new Date());
  }, 1000);
}
