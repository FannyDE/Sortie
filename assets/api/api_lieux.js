window.onload = () => {
    const villeSelect = document.querySelector('#ville_nom');
    const lieuSelect = document.querySelector('#lieu_nom');
    const infosLieu = document.querySelector('#infos_lieu');

    villeSelect.addEventListener('change', function() {
        fetch(`/Sortie/public/lieux/${this.value}`, {
            method: 'GET',
            headers: { 'Accept' : 'application-json' }
        }).then(response => response.json())
            .then(data => {
                lieuSelect.innerHTML = '<option value="">Sélectionnez un lieu</option>';
                data.forEach(lieu => {
                    const option = document.createElement('option');
                    option.value = lieu.id;
                    option.textContent = lieu.nom;
                    lieuSelect.appendChild(option);
                });
            })
    })
    .catch(e => {
        alert('ERREUR')
    })

    lieuSelect.addEventListener('change', () => {
        fetch(`/Sortie/public/lieu/${this.value}`, {
            method: 'GET',
            headers: { 'Accept' : 'application-json'}
        }).then(response => response.json())
            .then(data => {
                infosLieu.innerHTML = '';
                data.forEach(info => {
                    const p = document.createElement('p');
                    p.textContent = `Rue : ${info.rue}`;
                    infosLieu.appendChild(p);
                })
            })
    })
    .catch(e => {
        alert('ERREUR : problème chargement infos')
    })
}


/*
let villeValue = null;
    const villes = document.querySelector('#villes_list'); 
    villes.addEventListener("change", () => {
        fetch('http://localhost:8888/Sortie/public/sortie/api/lieux', {
            method: "GET",
            headers: { 'Accept': 'application-json' }
        }).then(response => response.json())
            .then(response => {
                response.map(lieu => {
                    options += `<option value="${lieu.id}">${lieu.nom} ${lieu.ville}</option>`
                    console.log(response)
                })
                console.log(villeValue)
                document.querySelector('#lieux').innerHTML = options;
            })
            .catch(e => {
                alert('ERREUR')
            })
    }) 
*/