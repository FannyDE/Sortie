window.onload = () => {
    const villeSelect = document.querySelector('#sortie_ville_nom');
    const lieuSelect = document.querySelector('#sortie_lieu');
    const rue = document.querySelector('#info_rue');
    const cp = document.querySelector('#info_cp');
    const latitude = document.querySelector('#info_latitude');
    const longitude = document.querySelector('#info_longitude');

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

    lieuSelect.addEventListener('change', function() {
        fetch(`/Sortie/public/lieu/${this.value}`, {
            method: 'GET',
            headers: { 'Accept' : 'application-json' }
        }).then(response => response.json())
            .then(data => {
                rue.innerHTML = data[0].rue;
                cp.innerHTML = data[0].codePostal;
                latitude.innerHTML = data[0].latitude;
                longitude.innerHTML = data[0].longitude;
            });
    })
}