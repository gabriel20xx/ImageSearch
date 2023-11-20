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
    var modelElement = document.getElementsByName('model')[0]; // Assuming there is only one element with the name 'model'

    if (selectedFilter === 'PositivePrompt' || selectedFilter === 'NegativePrompt') {
        searchElement.style.display = 'block';
    } else {
        searchElement.style.display = 'none';
    }

    if (selectedFilter === 'Model') {
        modelElement.style.display = 'block';
    } else {
        modelElement.style.display = 'none';
    }
}

$(".add").click(function() {
    $("form > p:first-child").clone(true).insertBefore("form > p:last-child");
    return false;
});

$(".remove").click(function() {
    $(this).parent().remove();
});
