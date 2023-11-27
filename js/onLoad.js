// Execute this on page load
document.addEventListener("DOMContentLoaded", function () {
  var initialFilter = document.getElementById("filter").value;
  var initialMinMax = document.getElementById("minmaxrange-input").value;

  handleFilterChange(initialFilter);
  handleMinMaxRangeChange(initialMinMax);
});

