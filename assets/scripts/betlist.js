document.addEventListener("DOMContentLoaded", function () {
    const betButtons = document.querySelectorAll(".bet-button");
    const addBetButton = document.getElementById("add-bet-button");
    const betSelectionList = document.getElementById("bet-selection");
    const betAmountInput = document.getElementById("bet-amount");
    const potentialWin = document.getElementById("potential-win");

    // Fonction pour mettre à jour la somme potentielle à gagner pour chaque pari
    function updatePotentialWins() {
        const betAmount = parseFloat(betAmountInput.value);
        if (isNaN(betAmount) || betAmount <= 0) {
            // Si la mise est supprimée ou n'est pas un nombre valide, réinitialiser le gain potentiel à zéro
            potentialWin.textContent = "$0.00";
            return;
        }

        let totalPotentialWin = betAmount; // Initialise la somme totale des gains potentiels avec le montant misé

        betSelectionList.querySelectorAll("li").forEach(function (li) {
            const odds = parseFloat(li.getAttribute("data-odds"));
            totalPotentialWin *= odds; // Multiplie la cote avec le montant total
        });

        // Mettre à jour la somme potentielle totale à gagner
        potentialWin.textContent = `${totalPotentialWin.toFixed(2)}€`;
    }

    // Récupérer les paris stockés localement et les ajouter à la liste de sélection
    function loadStoredBets() {
        const storedBets = JSON.parse(localStorage.getItem("storedBets"));
        if (storedBets) {
            storedBets.forEach(function (bet) {
                const li = createBetListItem(bet.team, bet.odds, bet.matchId, bet.matchName);
                betSelectionList.appendChild(li);
            });
        }
    }

    // Sauvegarder les paris actuels localement
    function saveCurrentBets() {
        const bets = [];
        betSelectionList.querySelectorAll("li").forEach(function (li) {
            const team = li.getAttribute("data-team");
            const odds = parseFloat(li.getAttribute("data-odds"));
            const matchId = li.getAttribute("data-match");
            const matchName = li.querySelector(".match-name").textContent;
            bets.push({ team, odds, matchId, matchName });
        });
        localStorage.setItem("storedBets", JSON.stringify(bets));
    }

    // Créer un élément de liste pour un pari
    function createBetListItem(team, odds, matchId, matchName) {
        const li = document.createElement("li");
        li.classList.add("bg-blue-950", "rounded", "border", "p-4", "mb-4", "relative");

        const matchSpan = document.createElement("span");
        matchSpan.textContent = matchName;
        matchSpan.classList.add("match-name");
        li.appendChild(matchSpan);

        const teamParagraph = document.createElement("p");
        teamParagraph.textContent = `Résultat : ${team} (${odds})`;
        teamParagraph.classList.add("team-odds");
        li.appendChild(teamParagraph);

        const removeButton = document.createElement("button");
        removeButton.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none" stroke="#4d7c0f" stroke-width="2" class="h-6 w-6">
        <path d="M6 6l8 8M14 6l-8 8"/>
    </svg>`;
        removeButton.classList.add("remove-bet-button", "absolute", "top-0", "right-0", "p-2", "text-lime-600");

        removeButton.addEventListener("click", function () {
            li.remove();
            updatePotentialWins();
            saveCurrentBets();
        });

        li.appendChild(removeButton);

        li.setAttribute("data-team", team);
        li.setAttribute("data-odds", odds);
        li.setAttribute("data-match", matchId);

        return li;
    }

    // Charger les paris stockés localement lors du chargement de la page
    loadStoredBets();

    // Gestionnaire d'événement pour les boutons de pari
    betButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            const team = button.getAttribute("data-team");
            const odds = parseFloat(button.getAttribute("data-odds"));
            const matchId = button.getAttribute("data-match");
            const matchName = button.getAttribute("data-match-name");

            const existingBetOnMatch = betSelectionList.querySelector(`li[data-match="${matchId}"]`);

            if (existingBetOnMatch) {
                alert("Vous avez déjà parié sur un gagnant dans ce match.");
                return;
            }

            const li = createBetListItem(team, odds, matchId, matchName);

            betSelectionList.appendChild(li);
            updatePotentialWins();
            saveCurrentBets();
        });
    });

    // Gestionnaire d'événement pour le bouton "Ajouter au panier"
    addBetButton.addEventListener("click", function () {
        betSelectionList.innerHTML = "";
        betAmountInput.value = "";
        potentialWin.textContent = "0.00€";
        localStorage.removeItem("storedBets");
    });

    // Gestionnaire d'événement pour le changement du montant de la mise
    betAmountInput.addEventListener("input", updatePotentialWins);
});
