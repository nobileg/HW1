function onJsonAnnunci(json) {
    const section = document.querySelector('#board dl')
    for (let row of json) {
        let dt = document.createElement('dt')
        let data = document.createElement('em')
        data.textContent = row['Data']
        dt.appendChild(data)
        let author = document.createElement('span')
        author.textContent = " — " + row['Nome'] + " " + row['Cognome']
        dt.appendChild(author)
        let message = document.createElement('dd')
        message.textContent = row['Messaggio']
        section.appendChild(dt)
        if (row['Immagine']) {
            let image_div = document.createElement('div')
            let image = document.createElement('img')
            image.src = row['Immagine']
            image_div.appendChild(image)
            section.appendChild(image_div)
        }
        section.appendChild(message)
    }
}

function onResponse(response) {
    return response.json()
}

function onTextInput(event) {
    event.currentTarget.style.height = "auto"
    event.currentTarget.style.height = (event.currentTarget.scrollHeight) + "px"
}

function showSearchImage(event) {
    const search_div = document.querySelector('#search_container')
    search_div.classList.remove('hidden')
    event.currentTarget.classList.add('hidden')
}

function selectImage(event) {
    const container = document.querySelector('#result_container')
    for (child of container.childNodes) {
        child.classList.add('unselected')
    }
    event.currentTarget.classList.remove('unselected')
    const message_image = document.querySelector('#message_image')
    message_image.value = event.currentTarget.src
}

function onJsonSearch(json) {
    console.log(json)
    const container = document.querySelector('#result_container')
    for (let row of json) {
        let image = document.createElement('img')
        image.src = row
        image.addEventListener('click', selectImage)
        container.appendChild(image)
    }
}

function searchImage(event) {
    event.preventDefault();
    if (search_form.query.value.length > 0) {
        fetch("search_image.php?q=" + encodeURIComponent(search_form.query.value)).then(onResponse).then(onJsonSearch)
    }
}

function checkForm(event) {
    if (message_form.message.value.length <= 0) {
        event.preventDefault()
    }
}


fetch("fetch_annunci.php").then(onResponse).then(onJsonAnnunci)
const textarea = document.querySelector('textarea')
textarea.addEventListener('input', onTextInput)
const a_search = document.querySelector('#a_search')
a_search.addEventListener('click', showSearchImage)
const search_form = document.forms['search_image']
search_form.addEventListener('submit', searchImage)
const message_form = document.forms['announcement']
message_form.addEventListener('submit', checkForm)