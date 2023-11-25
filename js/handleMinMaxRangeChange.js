// Modified handleFilterChange function to toggle visibility
function handleMinMaxRangeChange(selectedFilter) {
    const oneValueElement = document.getElementById("#oneValueForm");
    const twoValueElement = document.getElementById("#twoValueForm");
    const oneValueInput = document.getElementById("#oneValueInput");
    const lowerValueInput = document.getElementById("#lowerValueInput");
    const upperValueInput = document.getElementById("#upperValueInput");
  
    // Disable and make readonly by default
    oneValueInput.disabled = true;
    lowerValueInput.disabled = true;
    upperValueInput.disabled = true;
  
    // Set default visibility to false
    oneValueElement.style.display = "none";
    twoValueElement.style.display = "none";
  
    // Set visibility based on the selected filter
    switch (selectedFilter) {
      case "Min":
      case "Max":
        oneValueElement.style.display = "block";
        oneValueInput.disabled = false;
        break;
  
      case "Range":
        twoValueElement.style.display = "block"
        lowerValueInput.disabled = false;
        upperValueInput.disabled = false;
        break;
  
      default:
        break;
    }
  }