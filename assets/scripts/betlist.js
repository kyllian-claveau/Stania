document.addEventListener("DOMContentLoaded", function () {
    const betButtons = document.querySelectorAll(".bet-button");
    const betAmountInput = document.getElementById("bet-amount");
    const potentialWin = document.getElementById("potential-win");
    const defaultImage = document.getElementById("default-image");
    const betForm = document.getElementById("bet-form");
    const betSelectionList = document.getElementById("bet-selection");
    const addBetButton = document.getElementById("add-bet-button");

    betAmountInput.addEventListener("input", function () {
        updatePotentialWins();
    });

    function toggleDefaultImageVisibility() {
        if (betSelectionList.children.length === 0) {
            defaultImage.style.display = "block";
        } else {
            defaultImage.style.display = "none";
        }
    }

    function updatePotentialWins() {
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

    function loadStoredBets() {
        const storedBets = JSON.parse(localStorage.getItem("storedBets"));
        if (storedBets) {
            storedBets.forEach(function (bet) {
                const li = createBetListItem(bet.team, bet.odds, bet.matchId, bet.matchName);
                betSelectionList.appendChild(li);
            });
        }
        toggleDefaultImageVisibility();
    }

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
        toggleDefaultImageVisibility();
    }

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

    loadStoredBets();
    toggleDefaultImageVisibility();

    console.log("Nombre de boutons de pari trouvés:", betButtons.length);
    betButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            console.log("Bouton de pari cliqué");
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
            toggleDefaultImageVisibility();
        });
    });

    // Gestionnaire d'événement pour le bouton "Ajouter au panier"
    addBetButton.addEventListener("click", function () {
        event.preventDefault();
        const formData = new FormData(betForm);
        const betSelections = getBetSelections();
        formData.append('betSelections', JSON.stringify(betSelections));
        formData.append('betAmount', betAmountInput.value);
        formData.append('potentialWin', potentialWin.textContent);

        console.log('Données à envoyer en base de données:', Array.from(formData.entries()));

        fetch(betForm.action, {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Bet saved:', data);
                // Réinitialiser le formulaire ou effectuer d'autres actions si nécessaire
                betForm.reset();
                betSelectionList.innerHTML = "";
                window.location.reload();
            })
            .catch(error => {
                console.error('Error saving bet:', error);
            });
    });

    function getBetSelections() {
        const betSelections = [];
        betSelectionList.querySelectorAll("li").forEach(function (li) {
            const team = li.getAttribute("data-team");
            const odds = parseFloat(li.getAttribute("data-odds"));
            const matchId = li.getAttribute("data-match");
            const matchName = li.querySelector(".match-name").textContent;
            betSelections.push({ team, odds, matchId, matchName });
        });
        return betSelections;
    }
});
