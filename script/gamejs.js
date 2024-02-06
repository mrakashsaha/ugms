document.addEventListener("DOMContentLoaded", function() {
    const gameNameSelect = document.getElementById("game_name");
    const boardSelect = document.getElementById("board");
    const playersNumberInput = document.getElementById("players_number");
    const appointmentDateInput = document.getElementById("appointment_date");

    // Event listener for game name select change
    gameNameSelect.addEventListener("change", function() {
        const selectedGame = gameNameSelect.value;
        let boards = [];

        // Generate board IDs based on selected game
        switch (selectedGame) {
            case "Table Tennis":
                boards = Array.from({ length: 2 }, (_, i) => i + 1);
                break;
            case "Chess":
                boards = Array.from({ length: 5 }, (_, i) => i + 1);
                break;
            case "Carrom":
                boards = Array.from({ length: 4 }, (_, i) => i + 1);
                break;
            case "Foosball":
                boards = Array.from({ length: 2 }, (_, i) => i + 1);
                break;
            default:
                boards = [];
        }

        // Update board select options
        boardSelect.innerHTML = '';
        boards.forEach(board => {
            const option = document.createElement('option');
            option.value = board;
            option.textContent = `Board ${board}`;
            boardSelect.appendChild(option);
        });

        // Set players number based on selected game
        switch (selectedGame) {
            case "Table Tennis":
                playersNumberInput.value = 4;
                break;
            case "Chess":
                playersNumberInput.value = 2;
                break;
            case "Carrom":
                playersNumberInput.value = 4;
                break;
            case "Foosball":
                playersNumberInput.value = 2;
                break;
            default:
                playersNumberInput.value = "";
        }
    });

    // Event listener for appointment date change
    appointmentDateInput.addEventListener("change", function() {
        // Reset time slot when the date changes
        document.getElementById("slot").selectedIndex = 0;
    });
});
