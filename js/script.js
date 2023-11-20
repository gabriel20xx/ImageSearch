// Use const and let for variable declarations
const images = []; // assuming you have an array of images

function setDisplayMode(mode) {
    window.location.href = `index.php?display_mode=${mode}`;
}

let currentImageIndex = 0; // Index of the currently displayed image

// Function to open fullscreen with a specific image
function openFullscreen(imageSrc) {
    const fullscreenContainer = document.getElementById('fullscreenContainer');
    const fullscreenImage = document.getElementById('fullscreenImage');

    fullscreenImage.src = imageSrc;
    fullscreenContainer.style.display = 'grid';
}

// Function to close fullscreen
function closeFullscreen() {
    const fullscreenContainer = document.getElementById('fullscreenContainer');
    fullscreenContainer.style.display = 'none';
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

document.addEventListener('DOMContentLoaded', function () {
    // Use querySelectorAll to select multiple elements and loop through them
    document.querySelectorAll('.search-form, .model-form, .slider-form').forEach(function (element) {
        element.style.display = 'none';
    });
});

function handleFilterChange(selectedFilter) {
    const searchElement = document.querySelector('.search-form');
    const modelElement = document.querySelector('.model-form');
    const sliderElement = document.querySelector('.slider-form');

    // Use a switch statement for better readability
    switch (selectedFilter) {
        case 'PositivePrompt':
        case 'NegativePrompt':
        case 'Filename':
            searchElement.style.display = 'block';
            break;

        case 'ModelHash':
        case 'Model':
        case 'SeedResizeFrom':
        case 'DenoisingStrength':
            modelElement.style.display = 'block';
            break;

        case 'NSFWProbability':
            sliderElement.style.display = 'block';
            break;

        default:
            break;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    console.log('Script is running');

    // Use event delegation to handle click events on the form
    document.querySelector('form').addEventListener('click', function (event) {
        const target = event.target;

        if (target.classList.contains('remove')) {
            console.log('Remove button clicked');
            target.closest('.row').remove();
        }

        if (target.classList.contains('add')) {
            console.log('Add button clicked');
            const clonedElement = document.querySelector('form > .container:first-child').cloneNode(true);
            clonedElement.innerHTML += '<button type="button" class="remove-row btn btn-danger">Remove</button>';
            document.querySelector('form > .container:last-child').insertAdjacentElement('afterend', clonedElement);
        }
    });
});
