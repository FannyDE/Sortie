const villeSelect = document.querySelector('#sortie_ville');
const lieuSelect = document.querySelector('#sortie_lieu');
const rue = document.querySelector('#info_rue');
const cp = document.querySelector('#info_cp');
const latitude = document.querySelector('#info_latitude');
const longitude = document.querySelector('#info_longitude');

villeSelect.addEventListener('change', function() {
    var villeId = this.value;
    if (villeId) {
        var xhr = new XMLHttpRequest();
        var url = 'http://localhost:8888/Sortie/public/api/lieux/' + villeId;
        xhr.open('POST', url, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var lieux = JSON.parse(xhr.responseText);
                var selectLieux = document.getElementById('sortie_lieu');
                selectLieux.innerHTML = '';
                var option = document.createElement('option');
                option.value = "";
                option.textContent = "Sélectionnez un lieu";
                selectLieux.appendChild(option);
                
                // Trier les lieux par ordre alphabétique du nom
                lieux.sort((a, b) => a.nom.localeCompare(b.nom));
                
                // Ajouter les options triées au menu déroulant
                lieux.forEach(function(lieu) {
                    var option = document.createElement('option');
                    option.value = lieu.id;
                    option.textContent = lieu.nom;
                    selectLieux.appendChild(option);
                });
            }
        };
        xhr.send();
    } else {
        var selectLieux = document.getElementById('sortie_lieu');
        selectLieux.innerHTML = '';
        var option = document.createElement('option');
        option.value = "";
        option.textContent = "Sélectionnez un lieu";
        selectLieux.appendChild(option);
    }
    rue.innerHTML = "";
    cp.innerHTML = "";
    latitude.innerHTML = "";
    longitude.innerHTML = "";
})


lieuSelect.addEventListener('change', function() {
    var lieuId = this.value;
    if (lieuId) {
        fetch(`/Sortie/public/api/lieu/${lieuId}`, {
            method: 'GET',
            headers: { 'Accept' : 'application-json' }
        }).then(response => response.json())
        .then(data => {
            rue.innerHTML = data[0].rue;
            cp.innerHTML = data[0].codePostal;
            latitude.innerHTML = data[0].latitude;
            longitude.innerHTML = data[0].longitude;
        });
    }
    rue.innerHTML = "";
    cp.innerHTML = "";
    latitude.innerHTML = "";
    longitude.innerHTML = "";
})