/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

/* ------------- Register Town  Autocomplete Js ------------------- */
(function() {
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
})();
/* ------------- End of Autocomplete Js ---------------------------- */

/* ---------------- Search Trip Town  Autocomplete Js -------------- */

/* function(){
    document.addEventListener('DOMContentLoaded'){
        
    } */

