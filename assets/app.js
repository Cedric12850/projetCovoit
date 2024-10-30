import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');


document.addEventListener('DOMContentLoaded', function() {
    const zipCodeInput = document.querySelector('.js-zip-code');
    const townSelect = document.getElementById('town-select');

    if (zipCodeInput) {
        zipCodeInput.addEventListener('input', function() {
            const zipCode = this.value;
            if (zipCode.length >= 1) {
                fetch(`/search-towns?zip_code=${encodeURIComponent(zipCode)}`)
                    .then(response => response.json())
                    .then(towns => {
                        updateTownSelect(towns);
                    })
                    .catch(error => console.error('Erreur:', error));
            } else {
                hideTownSelect();
            }
        });
    }

    function updateTownSelect(towns) {
        townSelect.innerHTML = '<option value="">Sélectionnez une ville</option>';
        if (towns.length > 0) {
            towns.forEach(town => {
                const option = document.createElement('option');
                option.value = town.id;
                option.textContent = town.name + ' (' + town.zip_code + ')';
                townSelect.appendChild(option);
            });
            showTownSelect();
        } else {
            hideTownSelect();
        }
    }

    function showTownSelect() {
        townSelect.style.display = 'block';
    }

    function hideTownSelect() {
        townSelect.style.display = 'none';
    }
});