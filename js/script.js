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

// Working
function handleFilterChange(selectedFilter) {
    var searchElement = document.querySelector('.search-form');
    var modelElement = document.querySelector('.model-form');
    var sliderElement = document.querySelector('.slider-form');

    // Reset all elements to default display
    searchElement.classList.add("d-none");
    modelElement.classList.add("d-none");
    sliderElement.classList.add("d-none");

    if (selectedFilter === 'FileName' || selectedFilter === 'Directory' || selectedFilter === 'FileSize' || selectedFilter === 'PositivePrompt' || selectedFilter === 'NegativePrompt' || selectedFilter === 'All' || selectedFilter === 'Steps' || selectedFilter === 'Sampler' || selectedFilter === 'CFGScale' || selectedFilter === 'Seed' || selectedFilter === 'ImageSize' || selectedFilter === 'Version' || selectedFilter === 'NSFWProbability' || selectedFilter === 'SHA1' || selectedFilter === 'SHA256' || selectedFilter === 'MD5') {
        // Show the search element for specific filters
        searchElement.classList.remove("d-none");
    }

    if (selectedFilter === 'ModelHash' || selectedFilter === 'Model' || selectedFilter === 'SeedResizeFrom' || selectedFilter === 'DenoisingStrength') {
        // Show the model element for specific filters
        modelElement.classList.remove("d-none");
    }

    if (selectedFilter === 'NSFWProbability') {
        // Show the slider element for NSFWProbability
        sliderElement.classList.remove("d-none");
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

