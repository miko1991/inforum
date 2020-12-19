const handleDragOver = container => {
    container.addEventListener("dragover", e => {
        e.preventDefault();
        const draggable = document.querySelector(".dragging");
        const afterElement = getAfterElement(container, e.clientY);
        if (afterElement == null) {
            container.appendChild(draggable)
        } else {
            container.insertBefore(draggable, afterElement)
        }
    })
}

const getAfterElement = (container, y, x) => {
    const elements = [...container.querySelectorAll(".draggable:not(.dragging)")];
    return elements.reduce((closest, child) => {
        const box = child.getBoundingClientRect()
        const offsetX = x - box.left - box.width / 2;
        const offsetY = y - box.top - box.height / 2
        if (offsetY < 0 && offsetY > closest.offset) {
            return { offset: offsetY, element: child }
        } else {
            return closest
        }
    }, { offset: Number.NEGATIVE_INFINITY }).element
}