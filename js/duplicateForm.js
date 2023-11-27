document.addEventListener('click', function (event) {
    var target = event.target;
    if (target.classList.contains("remove-row")) {
        console.log("Remove button clicked");
        target.closest(".row").remove();
    }

    if (target.classList.contains("add-row")) {
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
})