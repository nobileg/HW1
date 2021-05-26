function onJson(json) {
    if (json['user_exists']) username_available = false
    else username_available = true
}

function onResponse(response) {
    return response.json()
}

function checkUsername(event) {
    fetch("validate_username.php?q=" + encodeURIComponent(event.currentTarget.value)).then(onResponse).then(onJson)
}

function validateData(event) {
    const errors = document.querySelector('#client_errors')
    errors.classList.add('hidden')
    errors.innerHTML = ""

    // Validazione campi
    if (!signup_form.username.value || !signup_form.email.value ||
        !signup_form.password.value || !signup_form.confirm_password.value ||
        !signup_form.firstname.value || !signup_form.lastname.value ||
        !signup_form.date.value || !signup_form.team.value) {
            const error_message = document.createElement('div')
            error_message.textContent = "Compila tutti i campi."
            errors.appendChild(error_message)
            errors.classList.remove('hidden')
            event.preventDefault()
        }

    // Validazione username
    if (signup_form.username.value.length <= 4) {
        const error_message = document.createElement('div')
        error_message.textContent = "L'username deve contenere almeno 5 caratteri."
        errors.appendChild(error_message)
        errors.classList.remove('hidden')
        signup_form.username.classList.add('input_error')
        event.preventDefault()
    }
    else if (!username_available) {
        const error_message = document.createElement('div')
        error_message.textContent = "L'username è già in uso."
        errors.appendChild(error_message)
        errors.classList.remove('hidden')
        signup_form.username.classList.add('input_error')
        event.preventDefault()
    }
    else signup_form.username.classList.remove('input_error')

    // Validazione password
    var psw_regex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/
    if (!psw_regex.test(signup_form.password.value)) {
        const error_message = document.createElement('div')
        error_message.textContent = "La password deve contenere almeno 8 caratteri, di cui almeno una lettera e un numero."
        errors.appendChild(error_message)
        errors.classList.remove('hidden')
        signup_form.password.classList.add('input_error')
        event.preventDefault()
    }
    else if (signup_form.confirm_password.value != signup_form.password.value) {
        const error_message = document.createElement('div')
        error_message.textContent = "Le due password non coincidono."
        errors.appendChild(error_message)
        errors.classList.remove('hidden')
        signup_form.password.classList.add('input_error')
        signup_form.confirm_password.classList.add('input_error')
        event.preventDefault()
    }
    else {
        signup_form.password.classList.remove('input_error')
        signup_form.confirm_password.classList.remove('input_error')
    }

}

const signup_form = document.forms['signup']
var username_available = false
signup_form.username.addEventListener('blur', checkUsername)
signup_form.addEventListener('submit', validateData)