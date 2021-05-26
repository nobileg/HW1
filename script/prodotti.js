function addInventory(event) {
    fetch("add_inventory.php?q=" + event.currentTarget.dataset.product)
    var inventory = event.currentTarget.parentNode.querySelector('h2.number')
    var number = parseInt(inventory.textContent)
    inventory.textContent = number + 1
}

function hideDetails(event) {
    const link = event.currentTarget
    const details = link.parentNode.querySelector('p')
    
    details.classList.add('hidden')
    link.textContent = 'Mostra dettagli'
    link.removeEventListener('click', hideDetails)
    link.addEventListener('click', showDetails)
}

function showDetails(event) {
    const link = event.currentTarget
    const details = link.parentNode.querySelector('p')
    
    details.classList.remove('hidden')
    link.textContent = 'Nascondi dettagli'
    link.removeEventListener('click', showDetails)
    link.addEventListener('click', hideDetails)
}

function onJson(json) {
    const section = document.querySelector('#products')
    for (row of json) {
        const article = document.createElement('article')
        const image = document.createElement('img')
        const title = document.createElement('h1')
        const div = document.createElement('div')
        const inventory = document.createElement('h2')
        const inventory_number = document.createElement('h2')
        const add_image = document.createElement('img')
        const content = document.createElement('p')
        const list_title = document.createElement('p')
        const ul = document.createElement('ul')
        const link = document.createElement('a')
        
        image.src = row['Immagine']
        title.textContent = row['Nome']
        inventory.textContent = "Lotti disponibili: "
        inventory_number.textContent = row['Lotti']
        inventory_number.classList.add('number')
        add_image.src = "img/add.png"
        add_image.dataset.product = row['Codice']
        add_image.classList.add('icon')
        add_image.addEventListener('click', addInventory)
        div.appendChild(inventory)
        div.appendChild(inventory_number)
        div.appendChild(add_image)
        content.textContent = row['Descrizione']
        list_title.textContent = "Teams assegnati:"
        for (let team of row['Teams']) {
            const li = document.createElement('li')
            li. textContent = team
            ul.appendChild(li)
        }
        content.appendChild(list_title)
        content.appendChild(ul)
        content.classList.add('hidden')
        link.textContent = 'Mostra dettagli'
        link.addEventListener('click', showDetails)

        article.appendChild(image)
        article.appendChild(title)
        article.appendChild(div)
        article.appendChild(content)
        article.appendChild(link)
        section.appendChild(article)
    }
}

function onResponse(response) {
    return response.json()
}

fetch("fetch_prodotti.php").then(onResponse).then(onJson)