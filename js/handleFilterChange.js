// Modified handleFilterChange function to toggle visibility
function handleFilterChange(selectedFilter) {
    const searchElement = document.querySelector(".search-form");
    const modelElement = document.querySelector(".model-form");
    const minmaxrangeElement = document.querySelector(".minmaxrange-form");
    const oneValueElement = document.querySelector(".oneValueForm");
    const twoValueElement = document.querySelector(".twoValueForm");
  
    const searchInput = document.querySelector(".search-input");
    const modelInput = document.querySelector(".model-input");
    const minmaxrangeInput = document.querySelector(".minmaxrange-input");
    const oneValueInput = document.querySelector(".oneValueInput");
    const lowerValueInput = document.querySelector(".lowerValueInput");
    const upperValueInput = document.querySelector(".upperValueInput");

    // Make subselects default
    minmaxrangeInput.querySelectorAll('option').forEach(option => {
        option.removeAttribute('selected');
    });
    
    // Set the selected attribute for the default option
    minmaxrangeInput.querySelector('option[value=""]').setAttribute('selected');
  
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