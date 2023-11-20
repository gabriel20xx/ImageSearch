function setDisplayMode(mode) {
    console.log('setDisplayMode called with mode:', mode);
    window.location.href = `index.php?display_mode=${mode}`;
}

function openFullscreen(imageSrc) {
    console.log('openFullscreen called with imageSrc:', imageSrc);
    var fullscreenContainer = document.createElement('div');
    fullscreenContainer.className = 'fullscreen';

    var imgElement = document.createElement('img');
    imgElement.src = imageSrc;

    fullscreenContainer.appendChild(imgElement);

    document.body.appendChild(fullscreenContainer);

    // Close fullscreen on click
    fullscreenContainer.addEventListener('click', function() {
        document.body.removeChild(fullscreenContainer);
    });
}

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