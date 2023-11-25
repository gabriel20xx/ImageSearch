// Execute this on page load
document.addEventListener("DOMContentLoaded", function () {
  var initialFilter = document.getElementById("filter").value;
  var initialMinMax = document.getElementById("minmaxrange-input").value;

  handleFilterChange(initialFilter);
  handleMinMaxRangeChange(initialMinMax);

  document.querySelector("form").addEventListener("click", function (event) {
    const target = event.target;

    if (target.classList.contains("remove")) {
      console.log("Remove button clicked");
      target.closest(".row").remove();
    }

    if (target.classList.contains("add")) {
      console.log("Add button clicked");
      const clonedElement = document
        .querySelector("form > .container:first-child")
        .cloneNode(true);
      clonedElement.innerHTML +=
        '<button type="button" class="remove-row btn btn-danger">Remove</button>';
      document
        .querySelector("form > .container:last-child")
        .insertAdjacentElement("afterend", clonedElement);
    }
  });
});

