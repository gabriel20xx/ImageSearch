document.addEventListener("DOMContentLoaded", function () {
  // Get the selected filter on page load
  var initialFilter = document.getElementById("filter").value;

  // Call the handleFilterChange function with the initial filter
  handleFilterChange(initialFilter);

  // Set up event listeners
  document.querySelector("form").addEventListener("submit", function (event) {
    // Call the updateFormAction function on form submit
    updateFormAction();
  });

  // Use event delegation to handle change events on the filter select element
  document.getElementById("filter").addEventListener("change", function () {
    const selectedFilter = this.value;
    // Call the handleFilterChange function on filter change
    handleFilterChange(selectedFilter);
  });

  // Use event delegation to handle click events on the form
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

// Use const and let for variable declarations
const images = []; // assuming you have an array of images

function setDisplayMode(mode) {
  window.location.href = `index.php?display_mode=${mode}`;
}

let currentImageIndex = 0; // Index of the currently displayed image

// Function to open fullscreen with a specific image
function openFullscreen(imageSrc) {
  const fullscreenContainer = document.getElementById("fullscreenContainer");
  const fullscreenImage = document.getElementById("fullscreenImage");

  fullscreenImage.src = imageSrc;
  fullscreenContainer.style.display = "grid";
}

// Function to close fullscreen
function closeFullscreen() {
  const fullscreenContainer = document.getElementById("fullscreenContainer");
  fullscreenContainer.style.display = "none";
}

// Function to show the previous image
function prevImage() {
  currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
  openFullscreen(images[currentImageIndex]);
}

// Function to show the next image
function nextImage() {
  currentImageIndex = (currentImageIndex + 1) % images.length;
  openFullscreen(images[currentImageIndex]);
}

document.addEventListener("DOMContentLoaded", function () {
  // Use querySelectorAll to select multiple elements and loop through them
  document
    .querySelectorAll(".search-form, .model-form, .slider-form")
    .forEach(function (element) {
      element.style.display = "none";
    });
});

// Existing updateFormAction function
function updateFormAction() {
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
}

// Modified handleFilterChange function to toggle visibility
function handleFilterChange(selectedFilter) {
  const searchElement = document.querySelector(".search-form");
  const modelElement = document.querySelector(".model-form");
  const sliderElement = document.querySelector(".slider-form");
  const searchInput = document.querySelector(".search-input");
  const modelInput = document.querySelector(".model-input");
  const sliderInput = document.querySelector(".slider-input");

  // Set default visibility to false
  searchElement.style.display = "none";
  modelElement.style.display = "none";
  sliderElement.style.display = "none";

  // Disable and make readonly by default
  disableInput(searchInput);
  disableInput(modelInput);
  disableInput(sliderInput);

  // Set visibility based on the selected filter
  switch (selectedFilter) {
    case "PositivePrompt":
    case "NegativePrompt":
    case "Filename":
      searchElement.style.display = "block";
      enableInput(searchInput);
      break;

    case "Model":
      modelElement.style.display = "block"
      enableInput(modelInput);
      break;

    case "DenoisingStrength":
    case "NSFWProbability":
      sliderElement.style.display = "block"
      enableInput(sliderInput);
      break;

    default:
      break;
  }
}

function disableInput(input) {
  input.readOnly = true;
  input.disabled = true;
}

function enableInput(input) {
  input.readOnly = false;
  input.disabled = false;
}
