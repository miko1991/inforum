const validateForm = (obj, data, form) => {
    resetErrors(form);
    for (let [key, values] of Object.entries(data)) {
        if (obj[key]) {
            obj[key].nextElementSibling.innerHTML = values.join("<br>");
        }
    }
}

const resetErrors = (form) => {
    const errors = form.querySelectorAll(".form__error")
    errors.forEach(error => {
        error.innerHTML = "";
    })
}