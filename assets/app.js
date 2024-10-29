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
    const input = document.getElementById('zipCodeTown');
    const autocompleteResponseBox = document.getElementById('autocompleteResponse');

    input.addEventListener('input', function() {
        const query = this.value;

        if (query.length < 2) {
            autocompleteResponseBox.innerHTML = 'Entrez les 2 premiers chiffres minimum';
            return; // Ne pas faire de requête si moins de 2 caractères
        }

        fetch(`/autocomplete/town?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                autocompleteResponseBox.innerHTML = '';
                data.forEach(town => {
                    const div = document.createElement('div');
                    div.textContent = town;
                    div.classList.add('autocompleteResponse');
                    div.addEventListener('click', () => {
                        input.value = town; // Remplir l'input avec la ville sélectionnée
                        autocompleteResponseBox.innerHTML = ''; // Vider les suggestions
                    });
                    autocompleteResponseBox.appendChild(div);
                });
            })
            .catch(error => console.error('Erreur:', error));
    });
});