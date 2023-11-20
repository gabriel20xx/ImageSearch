function setDisplayMode(mode) {
    window.location.href = `index.php?display_mode=${mode}`;
}

function openFullscreen(imageSrc) {
    var fullscreenContainer = document.getElementById('fullscreenContainer');
    var fullscreenImage = document.getElementById('fullscreenImage');

    fullscreenImage.src = imageSrc;
    fullscreenContainer.style.display = 'flex';
}

function closeFullscreen() {
    var fullscreenContainer = document.getElementById('fullscreenContainer');
    fullscreenContainer.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    var searchElement = document.querySelector('.search-form');
    var modelElement = document.querySelector('.model-form');
    var sliderElement = document.querySelector('.slider-form');

    searchElement.style.display = 'none';
    modelElement.style.display = 'none';
    sliderElement.style.display = 'none';
});

function handleFilterChange(selectedFilter) {
    var searchElement = document.querySelector('.search-form');
    var modelElement = document.querySelector('.model-form');
    var sliderElement = document.querySelector('.slider-form');

    searchElement.style.display = 'none';
    modelElement.style.display = 'none';
    sliderElement.style.display = 'none';

    if (selectedFilter === 'PositivePrompt' || selectedFilter === 'NegativePrompt' || selectedFilter === 'All') {
        searchElement.style.display = 'block';
    }

    if (selectedFilter === 'ModelHash' || selectedFilter === 'Model' || selectedFilter === 'SeedResizeFrom' || selectedFilter === 'DenoisingStrength') {
        modelElement.style.display = 'block';
    }

    if (selectedFilter === 'NSFWProbability') {
        sliderElement.style.display = 'block';
    }
}





document.addEventListener('DOMContentLoaded', function () {
    console.log("Script is running");

    document.querySelector("form").addEventListener("click", function (event) {
        var target = event.target;

        if (target.classList.contains("remove")) {
            console.log("Remove button clicked");
            target.closest('.row').remove();
        }

        if (target.classList.contains("add")) {
            console.log("Add button clicked");
            var clonedElement = document.querySelector("form > .container:first-child").cloneNode(true);
            clonedElement.innerHTML += '<button type="button" class="remove-row btn btn-danger">Remove</button>';
            document.querySelector("form > .container:last-child").insertAdjacentElement('afterend', clonedElement);
        }
    });
});

