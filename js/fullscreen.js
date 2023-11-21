// Fullscreen images
let currentImageIndex = 0;
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