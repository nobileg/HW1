function onJson(json) {
    const table = document.querySelector('#section_content table')
    for (const row of json) {
        let tr = document.createElement('tr')
        let td_nome = document.createElement('td')
        td_nome.textContent = row['Nome']
        tr.appendChild(td_nome)
        let td_sede = document.createElement('td')
        td_sede.textContent = row['Sede_Citta'] + ', ' + row['Sede_Indirizzo']
        tr.appendChild(td_sede)
        let td_leader = document.createElement('td')
        td_leader.textContent = row['Leader_Nome'] + ' ' + row['Leader_Cognome']
        tr.appendChild(td_leader)
        let td_assegnazione = document.createElement('td')
        td_assegnazione.textContent = row['Assegnazione']
        tr.appendChild(td_assegnazione)
        table.appendChild(tr)
    }
}

function onResponse(response) {
    return response.json()
}

fetch("fetch_teams.php").then(onResponse).then(onJson)