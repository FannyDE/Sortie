window.onload = function() {
    const campusSelect = document.querySelector('#registration_form_campus');

    // campusSelect.addEventListener('change', function() {
        fetch(`/Sortie/public/api/campus`, {
            method: 'GET',
            headers: { 'Accept' : 'application-json' }
        }).then(response => response.json())
            .then(data => {
                campusSelect.innerHTML = '<option value="">Sélectionnez un campus</option>';
                data.forEach(campus => {
                    const option = document.createElement('option');
                    option.value = campus.id;
                    option.textContent = campus.nom;
                    campusSelect.appendChild(option);
                });
            })
    // })
}