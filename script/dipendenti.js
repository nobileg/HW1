function onJson(json) {
    const table = document.querySelector('#section_content table')
    for (const row of json) {
        let tr = document.createElement('tr')
        let td_nome = document.createElement('td')
        td_nome.textContent = row['Nome']
        tr.appendChild(td_nome)
        let td_cognome = document.createElement('td')
        td_cognome.textContent = row['Cognome']
        tr.appendChild(td_cognome)
        let td_data = document.createElement('td')
        td_data.textContent = row['Data_Assunzione']
        tr.appendChild(td_data)
        let td_anni = document.createElement('td')
        td_anni.textContent = row['Anni_Servizio']
        tr.appendChild(td_anni)
        let td_team = document.createElement('td')
        td_team.textContent = row['Team']
        tr.appendChild(td_team)
        table.appendChild(tr)
    }
}

function onResponse(response) {
    return response.json()
}


fetch("fetch_dipendenti.php").then(onResponse).then(onJson)