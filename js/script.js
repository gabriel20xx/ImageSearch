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

    if (selectedFilter === 'PositivePrompt' || selectedFilter === 'NegativePrompt' || selectedFilter === 'All') {
        searchElement.classList.remove("invisible");
        searchElement.classList.add("visible");
    } else {
        searchElement.classList.add("invisible");
        searchElement.classList.remove("visible");
    }

    if (selectedFilter === 'ModelHash' || selectedFilter === 'Model' || selectedFilter === 'SeedResizeFrom' || selectedFilter === 'DenoisingStrength') {
        modelElement.classList.remove("invisible");
    } else {
        modelElement.classList.add("invisible");
    }
}


$(document).ready(function() {
    $("form").on("click", ".remove", function() {
        $(this).closest('.row').remove();
    });

    $(document).on("click", ".add-row", function() {
        var clonedElement = $("form > .container:first-child").clone(true);
        clonedElement.append('<button type="button" class="remove btn btn-danger">Remove</button>');
        clonedElement.insertAfter("form > .container:last-child");
        return false;
    });
});
