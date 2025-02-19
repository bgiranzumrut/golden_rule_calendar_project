document.addEventListener("DOMContentLoaded", function () {
    const galleryItems = document.querySelectorAll(".gallery-item");
    const galleryContainer = document.querySelector(".gallery-container");
    const body = document.body;

    galleryItems.forEach(item => {
        item.addEventListener("click", function () {
            // Remove previously selected small image
            document.querySelectorAll(".gallery-item").forEach(img => {
                img.classList.remove("selected");
            });

            // Mark this image as selected
            item.classList.add("selected");

            // Remove existing expanded view
            let existingExpanded = document.querySelector(".expanded-view");
            if (existingExpanded) existingExpanded.remove();

            // Get data attributes from the selected item
            const title = item.dataset.title || "Untitled";
            const category = item.dataset.category || "N/A";
            const description = item.dataset.description || "No description available";
            const date = item.dataset.date || "Unknown";

            // Create an expanded view
            let expandedView = document.createElement("div");
            expandedView.classList.add("expanded-view");

            // Populate expanded view with image and details
            expandedView.innerHTML = `
                <button class="close-btn">&times;</button>
                <img src="${item.querySelector('img').src}" alt="Expanded Image">
                <div class="content">
                    <h3>${title}</h3>
                    <p><strong>Category:</strong> ${category}</p>
                    <p><strong>Description:</strong> ${description}</p>
                    <p><strong>Posted Date:</strong> ${date}</p>
                </div>
            `;
            document.body.appendChild(expandedView);

            // Adjust layout for expanded mode
            galleryContainer.classList.add("expanded-mode");
            body.classList.add("expanded-mode");

            // Close expanded view
            expandedView.querySelector(".close-btn").addEventListener("click", function () {
                expandedView.remove();
                galleryContainer.classList.remove("expanded-mode");
                body.classList.remove("expanded-mode");
                item.classList.remove("selected");
            });
        });
    });
});
