const blocks = {};
let numBlocks = 0;
const content = document.getElementById("content");

const handleDragPageBuilder = container => {
    container.addEventListener("dragover", e => {
        e.preventDefault();
        const draggable = document.querySelector(".dragging");
        draggable.dataset.block_id = container.dataset.id;
        const afterElement = getAfterElement(container, e.clientY, e.clientX);
        if (afterElement == null) {
            container.appendChild(draggable)
        } else {
            container.insertBefore(draggable, afterElement)
        }
    })
}

const handleReorder = () => {
    console.log(blocks);
    const _blocks = [...content.querySelectorAll(".droppable")];
    _blocks.forEach((_block, blockIndex) => {
        for (const [key, value] of Object.entries(blocks)) {
            if (!value["columns"].length) {
                console.log("no columns")
                delete blocks[key];
                continue;
            }

            const _columns = [..._block.querySelectorAll(".draggable")];
            _columns.forEach((_column, _columnIndex) => {
                for (let column of value["columns"]) {
                    console.log(_column.dataset.id);
                    if (column.id === _column.dataset.id && column.block_id !== _column.dataset.block_id) {
                        console.log("here");
                        const theBlock = blocks[column.block_id]
                        if (theBlock) {
                            console.log("moving up or down")
                            column.block_id = _column.dataset.block_id;
                            blocks[_column.dataset.block_id]["columns"].splice(_columnIndex, 0, column);
                            const index = value["columns"].findIndex(c => c.id === column.id);
                            console.log(index)
                            theBlock["columns"].splice(index, 1)
                        }
                    } else if (column.id === _column.dataset.id && column.block_id === _column.dataset.block_id && column.order !== _columnIndex) {
                        console.log("same level")
                        column.order = _columnIndex;
                    }


                }

            })
        }
    })

    console.log(blocks);
}

const createNewBlock = (columns) => {
    if (!blocks["block_"+numBlocks]) {
        blocks["block_"+numBlocks] = {
            "order": null,
            "columns": []
        };
        for (let i = 0; i < columns; i++) {
            blocks["block_"+numBlocks]["columns"].push({});
        }
    }

    const block = document.createElement("div");
    block.dataset.id = "block_"+numBlocks;
    block.classList.add("droppable")
    block.classList.add("block");
    handleDragPageBuilder(block);

    blocks["block_"+numBlocks]["columns"].forEach((column, index) => {
        column["id"] = "column_"+index+"_"+numBlocks;
        column["order"] = index;
        column["block_id"] = "block_"+numBlocks;
        const columnElm = createColumnElement(block, columns)
        columnElm.dataset.id = "column_"+index+"_"+numBlocks;
        columnElm.dataset.block_id = "block_"+numBlocks;
        columnElm.classList.add("draggable");
        columnElm.draggable = true;
        columnElm.addEventListener("dragstart", () => {
            columnElm.classList.add("dragging");
        })

        columnElm.addEventListener("dragend", () => {
            columnElm.classList.remove("dragging");

            handleReorder()
        })
        // Sections

        const select = document.createElement("select");

        let optionDefault = document.createElement("option");
        optionDefault.value = "";
        optionDefault.textContent = "Choose Section";
        select.appendChild(optionDefault);

        const optionWidget = document.createElement("option");
        optionWidget.value = "widget";
        optionWidget.textContent = "Widget";
        select.appendChild(optionWidget);

        const optionMembersList = document.createElement("option");
        optionMembersList.value = "members_list";
        optionMembersList.textContent = "Members List";
        select.appendChild(optionMembersList)

        const optionLoginForm = document.createElement("option");
        optionLoginForm.value = "login_form";
        optionLoginForm.textContent = "Login Form";
        select.appendChild(optionLoginForm)

        const optionTextEditor = document.createElement("option");
        optionTextEditor.value = "text_editor";
        optionTextEditor.textContent = "Text Editor";
        select.appendChild(optionTextEditor)


        select.addEventListener("change", event => {
            let section;
            switch (event.target.value) {
                case "widget":
                    column["type"] = "widget";
                    break;
                case "members_list":
                    section = document.createElement("div");
                    section.textContent = "-- Members List --";
                    column["type"] = "members_list";
                    columnElm.appendChild(section);
                    break;
                case "login_form":
                    section = document.createElement("div");
                    section.textContent = "-- Login Form --";
                    column["type"] = "login_form";
                    columnElm.appendChild(section)
                    break;
                case "text_editor":
                    column["type"] = "text_editor";
                    break;
            }

            select.remove()

            createSettingsButton(column, index, columnElm)
        })


        columnElm.appendChild(select);

    })

    content.appendChild(block);
    numBlocks++;

}


const editBlocks = (parsedContent) => {
    numBlocks = 0;

    for (let [key, value] of Object.entries(parsedContent)) {
        const block = document.createElement("div");
        block.dataset.id = "block_"+numBlocks;
        block.classList.add("droppable");
        block.classList.add("block");
        handleDragPageBuilder(block)

        console.log(key)


        blocks[key] = {};
        blocks[key]["columns"] = value["columns"];
        blocks[key]["columns"].forEach((column, index) => {
            column["id"] = "column_"+index+"_"+numBlocks;
            column["order"] = index;
            column["block_id"] = "block_"+numBlocks;
            const columnElm = createColumnElement(block, value["columns"].length);
            columnElm.dataset.id = "column_"+index+"_"+numBlocks;
            columnElm.dataset.block_id = "block_"+numBlocks;
            columnElm.classList.add("draggable");
            columnElm.draggable = true;
            columnElm.addEventListener("dragstart", () => {
                columnElm.classList.add("dragging");
            })

            columnElm.addEventListener("dragend", () => {
                columnElm.classList.remove("dragging");

                handleReorder()
            })

            let options;
            switch (column.type) {
                case "widget":
                    options = document.createElement("div");
                    options.textContent = "-- Widget --";
                    columnElm.appendChild(options);
                    break;
                case "members_list":
                    options = document.createElement("div");
                    options.textContent = "-- Members List --";
                    columnElm.appendChild(options);
                    break;
                case "login_form":
                    options = document.createElement("div");
                    options.textContent = "-- Login Form --";
                    columnElm.appendChild(options)
                    break;
                case "text_editor":
                    options = document.createElement("div");
                    options.innerHTML = column.value;
                    columnElm.appendChild(options);
                    break;
            }

            createSettingsButton(column, index, columnElm)
        })

        content.appendChild(block);
        numBlocks++
    }
}



const createColumnElement = (block, columns) => {
    const columnElm = document.createElement("div");
    const width = columns === 1 ? "100" : columns === 2 ? "50" : "33";
    columnElm.classList.add("column")
    columnElm.classList.add("column-"+width);
    block.appendChild(columnElm);

    return columnElm;
}



const createSettingsButton = (column, index, columnElm) => {
    const settingsButton = document.createElement("button");
    settingsButton.classList.add("column__settings-btn");
    settingsButton.innerText = "Settings";
    settingsButton.type = "button";
    settingsButton.addEventListener("click", () => {
        const sidebar = document.getElementById("sidebar");
        sidebar.style.display = "none";
        const page_builder = document.getElementById("page_builder");
        page_builder.style.display = "flex";
        populatePageBuilder(column, index, columnElm);
    })

    columnElm.appendChild(settingsButton);
}

const populateHorizontalAlign = (column) => {
    // Horizontal Align

    const horizontalSelect = document.createElement("select");
    column["horizontal_align"] = "left";

    let optionLeft = document.createElement("option");
    optionLeft.value = "left";
    optionLeft.textContent = "Left";
    horizontalSelect.appendChild(optionLeft);

    let optionCenter = document.createElement("option");
    optionCenter.value = "center";
    optionCenter.textContent = "Center";
    horizontalSelect.appendChild(optionCenter);

    let optionRight = document.createElement("option");
    optionRight.value = "right";
    optionRight.textContent = "Right";
    horizontalSelect.appendChild(optionRight);

    horizontalSelect.addEventListener("change", event => {
        column["horizontal_align"] = event.target.value;
    })

    return horizontalSelect;
}

const populateTextEditor = (column, index) => {
    const section = document.createElement("div");
    section.style = "width: 100%; background-color: white; height: 300px;";
    section.id = "text_editor_"+numBlocks+"_"+index;
    return section;
}

const populateWidget = (column, columnElm) => {
    const container = document.createElement("div");
    let section = document.createElement("input");
    section.value = column["value"] ? column.value : "";
    section.placeholder = "Widget Name";
    container.appendChild(section);

    const results = document.createElement("div");
    results.classList.add("list");
    const handler = async event => {
        results.innerHTML = "";
        const response = await fetch("/autocomplete?table=plugin_widgets&column=title&query="+event.target.value);
        const widgets = await response.json();
        widgets.forEach(widget => {
            const result = document.createElement("div");
            result.classList.add("list__item");

            const title = document.createElement("span");
            title.innerText = widget.title;
            result.appendChild(title);

            const btn = document.createElement("button");
            btn.type = "button";
            btn.innerText = "Select";
            btn.addEventListener("click", () => {
                column["value"] = widget.id;
                column["title"] = widget.title;

                const article = columnElm.querySelector("article");
                if (article){
                    article.remove();

                }

                const widgetContainer = document.createElement("article");
                widgetContainer.innerText = "Widget: " + widget.title;
                columnElm.appendChild(widgetContainer);

                result.remove()
            })
            result.appendChild(btn);
            results.appendChild(result);
        })
    }
    const debouncedHandler = debounced(300, handler);
    section.addEventListener("keyup", debouncedHandler);

    container.appendChild(results);
    return container;
}

function debounced(delay, fn) {
    let timerId;
    return function (...args) {
        if (timerId) {
            clearTimeout(timerId);
        }
        timerId = setTimeout(() => {
            fn(...args);
            timerId = null;
        }, delay);
    }
}

const populatePageBuilder = (column, index, columnElm) => {
    const page_builder = document.getElementById("page_builder");
    page_builder.innerHTML = "";

    const sidebar = document.getElementById("sidebar");
    const closeButton = document.createElement("button");
    closeButton.type = "button";
    closeButton.innerText = "Close Page Builder";
    closeButton.addEventListener("click", () => {
        sidebar.style.display = "flex";
        page_builder.style.display = "none";
    })

    page_builder.appendChild(closeButton);

    const content = document.createElement("div");
    content.style.display = "flex";
    content.style.flexDirection = "column";

    const horizontal_align = populateHorizontalAlign(column);
    content.appendChild(horizontal_align);

    let options;
    switch (column["type"]) {
        case "widget":
            options = populateWidget(column, columnElm);
            content.appendChild(options)
            break;
        case "text_editor":
            options = populateTextEditor(column, index)
            content.appendChild(options);



            setTimeout(() => {
                const quill = new Quill('#'+options.id, {
                    theme: 'snow'
                });

                quill.on("text-change", () => {
                    const textContainer = columnElm.querySelector("article");
                    if (textContainer) {
                        textContainer.remove();
                    }

                    const article = document.createElement("article");
                    column["value"] = quill.container.firstChild.innerHTML;
                    article.innerHTML = quill.container.firstChild.innerHTML;
                    columnElm.appendChild(article);
                })


            }, 300)

            break;
    }

    page_builder.appendChild(content);
}

const newOneColumn = document.getElementById("new_one_column");
newOneColumn.addEventListener("click", () => {
    createNewBlock(1);
})

const newTwoColumns = document.getElementById("new_two_columns");
newTwoColumns.addEventListener("click", () => {
    createNewBlock(2);
})

const newThreeColumns = document.getElementById("new_three_columns");
newThreeColumns.addEventListener("click", () => {
    createNewBlock(3);
})


