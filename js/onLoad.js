// Execute this on page load
document.addEventListener("DOMContentLoaded", function () {
  var initialMinMax = document.getElementById("minmaxrange-input").value;
  var initialFilter = document.getElementById("filter").value;

  handleMinMaxRangeChange(initialMinMax);
  handleFilterChange(initialFilter);
});

