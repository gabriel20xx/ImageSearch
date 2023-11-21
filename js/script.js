let currentImageIndex = 0;

// Execute this on page load
document.addEventListener("DOMContentLoaded", function () {
  var initialFilter = document.getElementById("filter").value;
  handleFilterChange(initialFilter);

  document.querySelector("form").addEventListener("click", function (event) {
    const target = event.target;

    if (target.classList.contains("remove")) {
      console.log("Remove button clicked");
      target.closest(".row").remove();
    }

    if (target.classList.contains("add")) {
      console.log("Add button clicked");
      const clonedElement = document
        .querySelector("form > .container:first-child")
        .cloneNode(true);
      clonedElement.innerHTML +=
        '<button type="button" class="remove-row btn btn-danger">Remove</button>';
      document
        .querySelector("form > .container:last-child")
        .insertAdjacentElement("afterend", clonedElement);
    }
  });
});


// Fullscreen images
function openFullscreen(imageSrc) {
  const fullscreenContainer = document.getElementById("fullscreenContainer");
  const fullscreenImage = document.getElementById("fullscreenImage");

  fullscreenImage.src = imageSrc;
  fullscreenContainer.style.display = "grid";

  console.log("Opened fullscreen with image: " + imageSrc);
}

function closeFullscreen() {
  const fullscreenContainer = document.getElementById("fullscreenContainer");
  fullscreenContainer.style.display = "none";

  console.log("Closed fullscreen");
}

function prevImage() {
  currentImageIndex = (currentImageIndex - 1 + jsImages.length) % jsImages.length;
  openFullscreen(jsImages[currentImageIndex]);

  console.log("Previous Image. New Index: " + currentImageIndex);
}

function nextImage() {
  currentImageIndex = (currentImageIndex + 1) % jsImages.length;
  openFullscreen(jsImages[currentImageIndex]);

  console.log("Next Image. New Index: " + currentImageIndex);
}

console.log("Script loaded");


// Modified handleFilterChange function to toggle visibility
function handleFilterChange(selectedFilter) {
  const searchElement = document.querySelector(".search-form");
  const modelElement = document.querySelector(".model-form");
  const sliderElement = document.querySelector(".slider-form");
  const searchInput = document.querySelector(".search-input");
  const modelInput = document.querySelector(".model-input");
  const sliderInput = document.querySelector(".slider-input");

  // Disable and make readonly by default
  searchInput.disabled = true;
  modelInput.disabled = true;
  sliderInput.disabled = true;

  // Set default visibility to false
  searchElement.style.display = "none";
  modelElement.style.display = "none";
  sliderElement.style.display = "none";

  // Set visibility based on the selected filter
  switch (selectedFilter) {
    case "PositivePrompt":
    case "NegativePrompt":
    case "Filename":
      searchElement.style.display = "block";
      searchInput.disabled = false;
      break;

    case "Model":
      modelElement.style.display = "block"
      modelInput.disabled = false;
      break;

    case "DenoisingStrength":
    case "NSFWProbability":
      sliderElement.style.display = "block"
      sliderInput.disabled = false;
      break;

    default:
      break;
  }
}
