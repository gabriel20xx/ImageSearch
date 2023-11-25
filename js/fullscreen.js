// Fullscreen images
let currentImageIndex = 0;
let autoAdvanceEnabled = true;
let autoAdvanceTimeout;
function openFullscreen(imageSrc) {
  const fullscreenContainer = document.getElementById("fullscreenContainer");
  const fullscreenImage = document.getElementById("fullscreenImage");

  fullscreenImage.src = imageSrc;
  fullscreenContainer.style.display = "grid";

  console.log("Opened fullscreen with image: " + imageSrc);
  if (autoAdvanceEnabled) {
    setTimeout(nextImage, 3000);
  }
}

function closeFullscreen() {
  const fullscreenContainer = document.getElementById("fullscreenContainer");
  fullscreenContainer.style.display = "none";

  clearTimeout(autoAdvanceTimeout);

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

function toggleAutoAdvance() {
  autoAdvanceEnabled = !autoAdvanceEnabled;
  const toggleButton = document.getElementById("#toggleAutoAdvanceButton");
  toggleButton.textContent = autoAdvanceEnabled ? "Disable Auto-Advance" : "Enable Auto-Advance";
  console.log("Auto-Advance " + (autoAdvanceEnabled ? "enabled" : "disabled"));
}

console.log("Script loaded");