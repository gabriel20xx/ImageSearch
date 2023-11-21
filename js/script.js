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
const jsImages = [];
let currentImageIndex = 0;

function openFullscreen(imageSrc) {
  const fullscreenContainer = document.getElementById("fullscreenContainer");
  const fullscreenImage = document.getElementById("fullscreenImage");

  fullscreenImage.src = imageSrc;
  fullscreenContainer.style.display = "grid";
}

function closeFullscreen() {
  const fullscreenContainer = document.getElementById("fullscreenContainer");
  fullscreenContainer.style.display = "none";
}

function prevImage() {
  currentImageIndex = (currentImageIndex - 1 + jsImages.length) % jsImages.length;
  openFullscreen(jsImages[currentImageIndex]);
}

function nextImage() {
  currentImageIndex = (currentImageIndex + 1) % jsImages.length;
  openFullscreen(jsImages[currentImageIndex]);
}


// Existing updateFormAction function
/*function updateFormAction() {
  const form = document.querySelector("form");

  // Set default values for form elements
  let searchVisible = false;
  let modelVisible = false;
  let sliderVisible = false;

  // Determine visibility based on the selected filter
  const selectedFilter = form.filter.value;
  switch (selectedFilter) {
    case "PositivePrompt":
    case "NegativePrompt":
    case "FileName":
      searchVisible = true;
      break;

    case "Model":
      modelVisible = true;
      break;

    case "DenoisingStrength":
    case "NSFWProbability":
      sliderVisible = true;
      break;

    default:
      break;
  }

  // Update the form's action URL based on visibility
  form.action =
    "index.php?" +
    [
      searchVisible ? "search=" + encodeURIComponent(form.search.value) : "",
      modelVisible ? "model=" + encodeURIComponent(form.model.value) : "",
      sliderVisible ? "range=" + encodeURIComponent(form.range.value) : "",
      sliderVisible ? "range2=" + encodeURIComponent(form.range2.value) : "",
      "filter=" + encodeURIComponent(selectedFilter),
    ]
      .filter(Boolean)
      .join("&");

  return true; // Allow the form to be submitted
}*/


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
