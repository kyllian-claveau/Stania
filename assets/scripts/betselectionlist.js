document.addEventListener('DOMContentLoaded', function() {
    var selectedParties;
    const betAmountInput = document.getElementById("bet-amount");
    const potentialWin = document.getElementById("potential-win");
    const defaultImage = document.getElementById("default-image");
    const betSelectionList = document.getElementById("bet-selection");

    // Fonction pour mettre à jour le gain potentiel
    function updatePotentialWin() {
        const betAmount = parseFloat(betAmountInput.value);
        if (isNaN(betAmount) || betAmount <= 0) {
            potentialWin.textContent = "0.00€";
            return;
        }

        let totalPotentialWin = betAmount;

        betSelectionList.querySelectorAll("li").forEach(function (li) {
            const odds = parseFloat(li.getAttribute("data-odds"));
            totalPotentialWin *= odds;
        });

        potentialWin.textContent = `${totalPotentialWin.toFixed(2)}€`;

        toggleDefaultImageVisibility();
    }

    // Fonction pour afficher ou masquer l'image par défaut en fonction de la liste de sélection
    function toggleDefaultImageVisibility() {
        if (betSelectionList.children.length === 0) {
            defaultImage.style.display = "block";
        } else {
            defaultImage.style.display = "none";
        }
    }

    document.getElementById('prev-modal-button').addEventListener('click', function() {
        // Masquer la deuxième étape
        document.getElementById('step2').classList.add('hidden');
        // Afficher la première étape
        document.getElementById('step1').classList.remove('hidden');
    });

    document.getElementById('open-selection-modal').addEventListener('click', function() {
        selectedParties = document.querySelectorAll('input[name="selected-parties[]"]:checked');
        const modalList = document.getElementById('selected-parties-list');

        // Effacer le contenu précédent de la liste
        modalList.innerHTML = '';

        // Ajouter les matchs sélectionnés à la liste
        selectedParties.forEach(function(input) {
            const teamHomeName = input.closest('tr').querySelector('.teamHome').innerText;
            const teamAwayName = input.closest('tr').querySelector('.teamAway').innerText;
            const teamHomeOdds = input.closest('tr').querySelector('.teamHomeOdds').innerText;
            const drawOdds = input.closest('tr').querySelector('.drawOdds').innerText;
            const teamAwayOdds = input.closest('tr').querySelector('.teamAwayOdds').innerText;
            const partyDetails = teamHomeName + ' vs ' + teamAwayName;
            const partyId = input.value;

            const listItem = document.createElement('li');
            listItem.innerHTML = `
            <input type="checkbox" id="party-${partyId}" name="selected-parties[]" value="${partyId}" class="hidden peer" required />
            <label for="party-${partyId}" class="inline-flex items-center justify-between w-full p-5 text-gray-900 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-500 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white dark:bg-gray-600 dark:hover:bg-gray-500">
                <div class="block">
                    <div class="w-full text-lg font-semibold">${partyDetails}</div>
                </div>
            </label>`;
            modalList.appendChild(listItem);
        });
    });

    // Ajout de l'écouteur d'événement pour le bouton "Suivant"
    document.getElementById('next-modal-button').addEventListener('click', function() {
        // Masquer la première étape
        document.getElementById('step1').classList.add('hidden');
        // Afficher la deuxième étape
        document.getElementById('step2').classList.remove('hidden');

        // Récupérer les informations sur les matchs sélectionnés
        const selectedPartiesDetails = document.getElementById('selected-parties-details');

        // Effacer le contenu précédent
        selectedPartiesDetails.innerHTML = '';

        // Ajouter les détails des matchs sélectionnés
        selectedParties.forEach(function(input) {
            // Récupérer les informations sur le match
            const teamHomeName = input.closest('tr').querySelector('.teamHome').innerText;
            const teamAwayName = input.closest('tr').querySelector('.teamAway').innerText;
            const teamHomeOdds = input.closest('tr').querySelector('.teamHomeOdds').innerText;
            const drawOdds = input.closest('tr').querySelector('.drawOdds').innerText;
            const teamAwayOdds = input.closest('tr').querySelector('.teamAwayOdds').innerText;
            const partyDetails = teamHomeName + ' vs ' + teamAwayName;
            const partyId = input.value;


            // Créer un élément li pour afficher les détails du match
            const listItem = document.createElement('li');
            listItem.classList.add('border', 'rounded-lg', 'p-4', 'mb-2');

            // Afficher le titre du match
            const titleElement = document.createElement('div');
            titleElement.classList.add('font-semibold');
            titleElement.textContent = partyDetails;

            // Créer des boutons pour chaque équipe avec leurs cotes
            const oddsElement = document.createElement('div');
            oddsElement.classList.add('mt-2', 'grid', 'grid-cols-3', 'gap-2')
            oddsElement.innerHTML = `
                <button data-match-name="${teamHomeName} vs ${teamAwayName}" data-odds="${teamHomeOdds}" data-team="${teamHomeName}" data-match="${partyId}" class="btn-odd col-span-1">${teamHomeName} <br> ${teamHomeOdds}</button>
                <button data-match-name="${teamHomeName} vs ${teamAwayName}" data-odds="${drawOdds}" data-team="Match Nul" data-match="${partyId}" class="btn-odd col-span-1">Nul <br> ${drawOdds}</button>
                <button data-match-name="${teamHomeName} vs ${teamAwayName}" data-odds="${teamAwayOdds}" data-team="${teamAwayName}" data-match="${partyId}" class="btn-odd col-span-1">${teamAwayName} <br> ${teamAwayOdds}</button>`;

            // Ajouter les éléments au li
            listItem.appendChild(titleElement);
            listItem.appendChild(oddsElement);

            // Ajouter l'élément li à la liste des détails des matchs sélectionnés
            selectedPartiesDetails.appendChild(listItem);
        });

        // Ajouter un écouteur d'événement pour chaque bouton
        const oddButtons = document.querySelectorAll('.btn-odd');
        oddButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Désélectionner tous les autres boutons du match
                const matchId = button.getAttribute('data-match');
                const matchButtons = document.querySelectorAll(`[data-match="${matchId}"]`);
                matchButtons.forEach(function(matchButton) {
                    matchButton.classList.remove('selected');
                });

                // Sélectionner le bouton cliqué
                button.classList.add('selected');
            });
        });
    });

    // Récupérer le bouton pour ajouter à la liste de sélection
    const addToSelectionButton = document.querySelector('#step2 button');

    // Ajouter un écouteur d'événement au clic sur le bouton "Ajouter à la liste de sélection"
    addToSelectionButton.addEventListener('click', function() {
        // Récupérer les détails des matchs sélectionnés
        const selectedPartiesDetails = document.getElementById('selected-parties-details');

        // Parcourir les détails des matchs sélectionnés
        const selectedParties = selectedPartiesDetails.querySelectorAll('li');

        // Parcourir les détails des matchs sélectionnés et ajouter à la liste de sélection
        selectedParties.forEach(function(selectedParty) {
            // Récupérer le texte des détails du match sélectionné
            const partyDetails = selectedParty.querySelector('.font-semibold').textContent;
            const teamOdds = selectedParty.querySelector('.btn-odd.selected').getAttribute('data-odds');
            const teamName = selectedParty.querySelector('.btn-odd.selected').getAttribute('data-team');
            const partyId = selectedParty.querySelector('.btn-odd.selected').getAttribute('data-match');
            // Ajouter le détail du match sélectionné à la liste de sélection
            addPartyToSelectionList(partyDetails, teamName, teamOdds, partyId);
        });

        // Mettre à jour le gain potentiel
        updatePotentialWin();

        // Masquer l'image par défaut
        defaultImage.style.display = "none";

        // Afficher un message de confirmation
        alert('Les matchs sélectionnés ont été ajoutés à la liste de sélection.');
    });

    // Fonction pour ajouter un match à la liste de sélection
    function addPartyToSelectionList(partyDetails, team, odds, partyId) {
        // Créer un nouvel élément li pour le match sélectionné
        const listItem = document.createElement('li');
        listItem.classList.add("bg-blue-950", "rounded", "border", "p-4", "mb-4", "relative");

        const matchSpan = document.createElement("span");
        matchSpan.textContent = partyDetails;
        matchSpan.classList.add("match-name");
        listItem.appendChild(matchSpan);

        const teamParagraph = document.createElement("p");
        teamParagraph.textContent = `Résultat : ${team} (${odds})`;
        teamParagraph.classList.add("team-odds");
        listItem.appendChild(teamParagraph);

        // Ajouter la croix de suppression
        const removeButton = document.createElement('button');
        removeButton.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 absolute top-0 right-0 p-2 text-lime-600">
                <path d="M6 6l8 8M14 6l-8 8"/>
            </svg>`;
        removeButton.classList.add("remove-bet-button");
        removeButton.addEventListener('click', function() {
            listItem.remove();
            // Mettre à jour le gain potentiel après suppression
            updatePotentialWin();
        });
        listItem.appendChild(removeButton);

        // Ajouter les données au li
        listItem.setAttribute('data-team', team);
        listItem.setAttribute('data-odds', odds);
        listItem.setAttribute('data-match', partyId);

        // Ajouter le match à la liste de sélection
        betSelectionList.appendChild(listItem);
    }
});
