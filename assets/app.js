
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';


/* ------------- Recherche trajets dans Home ---------------------------- */
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('searchForm');
    var resultDiv = document.getElementById('resultRech');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Récupérer les valeurs des champs cachés
        var villeDep = document.getElementById('trip_search_town_start_id').value;
        var villeArr = document.getElementById('trip_search_town_end_id').value;

        // Vérification si les valeurs sont présentes
        if (!villeDep || !villeArr) {
            resultDiv.innerHTML = '<p>Veuillez sélectionner les villes de départ et d\'arrivée.</p>';
            return;  // Ne pas envoyer la requête si les villes ne sont pas sélectionnées
        }

        console.log('Ville de départ ID:', villeDep);
        console.log('Ville d\'arrivée ID:', villeArr);

        // Envoi de la requête AJAX
        fetch('/search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'trip_search_town_start_id=' + encodeURIComponent(villeDep) + 
                  '&trip_search_town_end_id=' + encodeURIComponent(villeArr)
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();  // Assurez-vous que la réponse est au format JSON
        })
        .then(function(trips) {
            if (!trips || !trips.tripsByDate || !trips.totalTrips) {
                throw new Error('Les données de la réponse sont mal formatées.');
            }

            // Construction du contenu HTML des résultats
            var html = '<p>Résultat de la recherche : </p>';
            html += '<span>' + trips.totalTrips + ' trajets sont disponibles : </span>';
            var count = 0;
            var nbAffiche = 10;
            var nbDates = Object.keys(trips.tripsByDate).length;
            var tripsTab = Object.entries(trips.tripsByDate);
            var dernier = nbAffiche < nbDates ? nbAffiche : nbDates;

            tripsTab.map((trip) => {
                if (count < nbAffiche) {
                    count++;
                    var formattedDate = new Date(trip[0]).toLocaleDateString('fr-FR');
                    html += '<span>' + formattedDate + ' : ' + trip[1];
                    html += count < dernier ? ', </span>' : ' </span>';
                }   
            });

            if (nbAffiche < nbDates) {
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

(function() {
    
/*         // Fonction pour afficher les suggestions dans une liste
        function showSuggestions(inputElement, suggestions) {
            // Supprimer les anciennes suggestions
            let existingList = inputElement.nextElementSibling;
            if (existingList && existingList.classList.contains('autocomplete-list')) {
                existingList.remove();
            }

            // Créer une nouvelle liste de suggestions
            const list = document.createElement('ul');
            list.classList.add('autocomplete-list');

            suggestions.forEach(function (town) {
                const listItem = document.createElement('li');
                listItem.textContent = town.name;
                listItem.addEventListener('click', function () {
                    inputElement.value = town.name; // Remplir le champ avec le nom de la ville
                    inputElement.dataset.selectedTownId = town.id; // Stocker l'ID de la ville dans un attribut data

                    // Mettre à jour le champ caché avec l'ID
                    const hiddenInputId = inputElement.name === 'trip_search[town_start]' ? 'town_start_id' : 'town_end_id';


                    console.log('Town ID:', town.id);  // Log pour vérifier l'ID de la ville
            console.log('Hidden Input ID:', hiddenInputId);  // Log pour vérifier l'ID du champ caché
            console.log('Updated hidden input value:', document.getElementById(hiddenInputId).value);  // Log pour vérifier la valeur mise à jour



                    document.getElementById(hiddenInputId).value = town.id; // Mettre à jour le champ caché avec l'ID

                    // Supprimer la liste des suggestions
                    list.remove();
                });
                list.appendChild(listItem);
            });

            // Ajouter la liste juste après le champ de saisie
            inputElement.parentNode.appendChild(list);
        }

        // Fonction pour récupérer les suggestions depuis l'API
        function fetchTowns(query, inputElement) {
            if (query.length < 3) return; // Lancer la recherche seulement si le texte a au moins 3 caractères

            fetch(`/api/towns?q=${encodeURIComponent(query)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur réseau');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        showSuggestions(inputElement, []); // Aucun résultat trouvé
                    } else {
                        showSuggestions(inputElement, data); // Afficher les suggestions
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des villes:', error);
                    showSuggestions(inputElement, []); // Optionnel: Afficher un message d'erreur si l'API échoue
                });
        }

        // Ajouter l'événement 'input' sur chaque champ de ville
        const townStartInput = document.querySelector('.town_autocomplete[name="trip_search[town_start]"]');
        const townEndInput = document.querySelector('.town_autocomplete[name="trip_search[town_end]"]');

        [townStartInput, townEndInput].forEach(function (inputElement) {
            inputElement.addEventListener('input', function () {
                const query = inputElement.value.trim();
                fetchTowns(query, inputElement); // Récupérer les suggestions
            });
        });
 */










    document.addEventListener('DOMContentLoaded', function () {
        // Sélectionner les champs d'autocomplétion pour les villes
        const townStartInput = document.querySelector('.town_autocomplete[name="trip_search[town_start]"]');
        const townEndInput = document.querySelector('.town_autocomplete[name="trip_search[town_end]"]');
        
        // Fonction pour afficher les suggestions dans une liste
        function showSuggestions(inputElement, suggestions) {
            // Supprimer les anciennes suggestions
            let existingList = inputElement.nextElementSibling;
            if (existingList && existingList.classList.contains('autocomplete-list')) {
                existingList.remove();
            }
    
            // Créer une nouvelle liste de suggestions
            const list = document.createElement('ul');
            list.classList.add('autocomplete-list');
    
            suggestions.forEach(function (town) {
                const listItem = document.createElement('li');
                listItem.textContent = town.name;
                listItem.addEventListener('click', function () {
                    inputElement.value = town.name; // Remplir le champ avec le nom de la ville
                    inputElement.dataset.selectedTownId = town.id; // Stocker l'ID de la ville

                        // Mettre à jour le champ caché avec l'ID

                    const hiddenInputId = inputElement.name === 'trip_search[town_start]' ? 'trip_search_town_start_id' : 'trip_search_town_end_id';
                    document.getElementById(hiddenInputId).value = town.id;

                    list.remove(); // Fermer la liste des suggestions
                });
                list.appendChild(listItem);
            });
    
            // Ajouter la liste juste après le champ de saisie
            inputElement.parentNode.appendChild(list);
        }
    
        // Fonction pour récupérer les suggestions depuis l'API
        function fetchTowns(query, inputElement) {
            if (query.length < 3) return; // Lancer la recherche seulement si le texte a au moins 3 caractères
    
            fetch(`/api/towns?q=${encodeURIComponent(query)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur réseau');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        showSuggestions(inputElement, []); // Aucun résultat trouvé
                    } else {
                        showSuggestions(inputElement, data); // Afficher les suggestions
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des villes:', error);
                    showSuggestions(inputElement, []); // Optionnel: Afficher un message d'erreur si l'API échoue
                });
        }
    
        // Ajouter l'événement 'input' sur chaque champ de ville
        [townStartInput, townEndInput].forEach(function (inputElement) {
            inputElement.addEventListener('input', function () {
                const query = inputElement.value.trim();
                fetchTowns(query, inputElement);
            });
        });
    });

})();



