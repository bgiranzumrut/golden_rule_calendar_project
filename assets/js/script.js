document.addEventListener("DOMContentLoaded", () => {
  const calendarCells = document.querySelectorAll("td");

  // Highlight cells with events
  calendarCells.forEach((cell) => {
    if (cell.querySelector(".event")) {
      cell.style.backgroundColor = "#fffbcc";
    }
  });

  // Add click-to-toggle event details
  document.querySelectorAll(".event").forEach((eventElement) => {
    eventElement.addEventListener("click", () => {
      const details = eventElement.querySelector(".details");
      if (details) {
        details.style.display =
          details.style.display === "none" ? "block" : "none";
      }
    });
  });
});
