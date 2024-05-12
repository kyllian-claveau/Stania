import './styles/app.css';
import 'flowbite';

document.addEventListener("DOMContentLoaded", function () {
    const betButton = document.querySelector(".bet-button");
    const radioInputs = document.querySelectorAll('input[name="job"]');

    radioInputs.forEach(function(input) {
        input.addEventListener("click", function() {
            const selectedTeam = input.getAttribute("data-team");
            const selectedOdds = input.getAttribute("data-odds");
            betButton.setAttribute("data-team", selectedTeam);
            betButton.setAttribute("data-odds", selectedOdds); // Ajout de cette ligne
            console.log("Data-team du bouton : ", betButton.getAttribute("data-team"));
            console.log("Data-odds du bouton : ", betButton.getAttribute("data-odds")); // Pour vérification
        });
    });
});


document.getElementById('commentBtn').addEventListener('click', function() {
    document.getElementById('comments').style.display = '';
    document.getElementById('infos').style.display = 'none';
    document.getElementById('compos').style.display = 'none';
    document.getElementById('edit').style.display = 'none';
});

document.getElementById('informationBtn').addEventListener('click', function() {
    document.getElementById('comments').style.display = 'none';
    document.getElementById('infos').style.display = '';
    document.getElementById('compos').style.display = 'none';
    document.getElementById('edit').style.display = 'none';
});

document.getElementById('compositionBtn').addEventListener('click', function() {
    document.getElementById('comments').style.display = 'none';
    document.getElementById('infos').style.display = 'none';
    document.getElementById('compos').style.display = '';
    document.getElementById('edit').style.display = 'none';
});

document.getElementById('editMatchBtn').addEventListener('click', function() {
    document.getElementById('comments').style.display = 'none';
    document.getElementById('infos').style.display = 'none';
    document.getElementById('compos').style.display = 'none';
    document.getElementById('edit').style.display = '';
});

document.addEventListener('DOMContentLoaded', function() {
    // Récupération du bouton "Miser"
    var betButton = document.getElementById('betBtn');

    // Vérification de l'existence du bouton
    if (betButton) {
        // Ajout d'un écouteur d'événement au clic sur le bouton
        betButton.addEventListener('click', function() {
            // Affichage de la section de mise sur le match
            var betSection = document.getElementById('betSection');
            if (betSection) {
                betSection.style.display = 'block';
            }
        });
    }
});


