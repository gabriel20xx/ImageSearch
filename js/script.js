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
    var searchElement = document.getElementById('search');
    var modelElement = document.getElementsByName('model')[0];
    var sliderElement = document.getElementById('slider');

    if (selectedFilter === 'PositivePrompt' || selectedFilter === 'NegativePrompt' || selectedFilter === 'All') {
        searchElement.classList.remove("d-none");
    } else {
        searchElement.classList.add("d-none");
    }

    if (selectedFilter === 'ModelHash' || selectedFilter === 'Model' || selectedFilter === 'SeedResizeFrom' || selectedFilter === 'DenoisingStrength') {
        modelElement.classList.remove("d-none");
    } else {
        modelElement.classList.add("d-none");
    }

    if (selectedFilter === 'NSFWProbability') {
        sliderElement.classList.remove("d-none");
    } else {
        sliderElement.classList.add("d-none");
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
            clonedElement.innerHTML += '<button type="button" class="remove btn btn-danger">Remove</button>';
            document.querySelector("form > .container:last-child").insertAdjacentElement('afterend', clonedElement);
        }
    });
});

