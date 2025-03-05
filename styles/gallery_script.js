document.addEventListener("DOMContentLoaded", function () {
  const galleryItems = document.querySelectorAll(".gallery-item img");
  const modal = document.createElement("div");
  modal.classList.add("modal");
  modal.innerHTML = `
          <span class="modal-close">&times;</span>
          <button class="modal-prev">&lt;</button>
          <img id="modal-img" src="">
          <button class="modal-next">&gt;</button>
      `;
  document.body.appendChild(modal);

  const modalImg = document.getElementById("modal-img");
  const closeModal = document.querySelector(".modal-close");
  const prevBtn = document.querySelector(".modal-prev");
  const nextBtn = document.querySelector(".modal-next");

  let currentIndex = 0;

  function updateModal(index) {
    if (index >= 0 && index < galleryItems.length) {
      modalImg.src = galleryItems[index].src;
      modal.style.display = "flex";
      currentIndex = index;
    }
  }

  // Open modal when an image is clicked
  galleryItems.forEach((img, index) => {
    img.addEventListener("click", () => {
      updateModal(index);
    });
  });

  // Close modal
  closeModal.addEventListener("click", () => {
    modal.style.display = "none";
  });

  // Close when clicking outside image
  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });

  // Navigate images using next/prev buttons
  prevBtn.addEventListener("click", () => {
    updateModal((currentIndex - 1 + galleryItems.length) % galleryItems.length);
  });

  nextBtn.addEventListener("click", () => {
    updateModal((currentIndex + 1) % galleryItems.length);
  });

  // Keyboard navigation
  document.addEventListener("keydown", (e) => {
    if (modal.style.display === "flex") {
      if (e.key === "ArrowRight") {
        updateModal((currentIndex + 1) % galleryItems.length);
      } else if (e.key === "ArrowLeft") {
        updateModal(
          (currentIndex - 1 + galleryItems.length) % galleryItems.length
        );
      } else if (e.key === "Escape") {
        modal.style.display = "none";
      }
    }
  });
});
