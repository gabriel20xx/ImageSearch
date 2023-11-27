// Modified handleFilterChange function to toggle visibility
function handleFilterChange(selectedFilter) {
    const searchElement = document.getElementById("search-form");
    const modelElement = document.getElementById("model-form");
    const minmaxrangeElement = document.getElementById("minmaxrange-form");
    const oneValueElement = document.getElementById("oneValueForm");
    const twoValueElement = document.getElementById("twoValueForm");
  
    const searchInput = document.getElementById("search-input");
    const modelInput = document.getElementById("model-input");
    const minmaxrangeInput = document.getElementById("minmaxrange-input");
    const oneValueInput = document.getElementById("oneValueInput");
    const lowerValueInput = document.getElementById("lowerValueInput");
    const upperValueInput = document.getElementById("upperValueInput");

    // Make subselects default
    minmaxrangeInput.querySelectorAll('option').forEach(option => {
        option.removeAttribute('selected');
    });
    
    // Set the selected attribute for the default option
    minmaxrangeInput.querySelector('option[value=""]').setAttribute('selected', 'selected');
  
    // Disable and make readonly by default
    searchInput.disabled = true;
    modelInput.disabled = true;
    minmaxrangeInput.disabled = true;
    oneValueInput.disabled = true;
    lowerValueInput.disabled = true;
    upperValueInput.disabled = true;
  
    // Set default visibility to false
    searchElement.style.display = "none";
    modelElement.style.display = "none";
    minmaxrangeElement.style.display = "none";
    oneValueElement.style.display = "none";
    twoValueElement.style.display = "none";
  
    // Set visibility based on the selected filter
    switch (selectedFilter) {
      case "PositivePrompt":
      case "NegativePrompt":
      case "Filename":
        searchElement.style.display = "block";
        searchInput.disabled = false;
        break;
  
      case "Model":
        modelElement.style.display = "block"
        modelInput.disabled = false;
        break;
  
      case "DenoisingStrength":
      case "NSFWProbability":
        minmaxrangeElement.style.display = "block"
        minmaxrangeInput.disabled = false;
        break;
  
      default:
        break;
    }
  }