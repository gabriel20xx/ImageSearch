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
    // Add any additional handling if needed
}