
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

/* ------------- Recherche trajets dans Home ---------------------------- */
document.addEventListener('DOMContentLoaded', function() {
var form = document.getElementById('searchForm');
var resultDiv = document.getElementById('resultRech');

form.addEventListener('submit', function(e) {
    e.preventDefault();
    var villeDep = document.getElementById('villeDep').value;
    var villeArr = document.getElementById('villeArr').value;

    fetch('/search', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'villeDep=' + encodeURIComponent(villeDep) + '&villeArr=' + encodeURIComponent(villeArr)
    })
    .then(function(response) {
        if (!response.ok) {
            throw new Error('Erreur réseau');
        }
        return response.json();
    })
    .then(function(trips) {
        var html = '<p>Résultat de la recherche : </p>';
        html += '<span>' + trips.totalTrips + ' trajets sont disponibles : </span>';
        var count = 0;
        var nbAffiche =  10;
        var nbDates = Object.keys(trips.tripsByDate).length;
        var tripsTab = Object.entries(trips.tripsByDate);
        var dernier = nbAffiche<nbDates? nbAffiche : nbDates;
        tripsTab.map((trip) => {
            if (count < nbAffiche) {
                count++;
                var formattedDate = new Date(trip[0]).toLocaleDateString('fr-FR');
                html += '<span>' + formattedDate + ' : ' + trip[1];
                html += count < dernier? ', </span>' : ' </span>';
            }   
        })
        if (nbAffiche < nbDates ) {
            html += '<span>...</span>';
        }
        resultDiv.innerHTML = html;
    })
    .catch(function(error) {
        console.error('Erreur:', error);
        resultDiv.innerHTML = '<p>Une erreur est survenue lors de la recherche.</p>';
    });
});
});

/* -------------   Autocomplete Js ---------------------------- */
document.addEventListener('DOMContentLoaded', function() {
    const zipCodeInput = document.querySelector('.js-zip-code');
    const townSelect = document.getElementById('town-select');
    const hiddenTownInput = document.querySelector('input[name="registration_form[town]"]');

        zipCodeInput.addEventListener('input', function() {
            const zipCode = this.value;
            if (zipCode.length >= 2) {
                fetch(`/search-towns?zip_code=${encodeURIComponent(zipCode)}`)
                    .then(response => response.json())
                    .then(towns => {
                        townSelect.innerHTML = '<option value="">Sélectionnez une ville</option>';
                        towns.forEach(town => {
                            const option = document.createElement('option');
                            option.value = town.id;
                            option.textContent = `${town.name} (${town.zip_code})`;
                            townSelect.appendChild(option);
                        });
                        townSelect.style.display = 'block';
                    });
            } else {
                townSelect.style.display = 'none';
            }
        });

        townSelect.addEventListener('change', function() {
            hiddenTownInput.value = this.value;
        });
    });
    
/* ------------- End of Autocomplete Js ---------------------------- */

/* import { Autocomplete } from '@symfony/ux-autocomplete'; */

document.addEventListener('DOMContentLoaded', () => {
    const autocompleteElements = document.querySelectorAll('.autocomplete');
    autocompleteElements.forEach(element => {
        new Autocomplete(element); // Simplement initialiser ici
    });
});