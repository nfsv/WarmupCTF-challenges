const boardElement = document.getElementById('game-board');
const statusElement = document.getElementById('status');
const resetButton = document.getElementById('reset-button');
const logoutButton = document.getElementById('logout-button');
const savePointsButton = document.getElementById('save-points-button');

const humanScoreElement = document.getElementById('human-score');
const computerScoreElement = document.getElementById('computer-score');

const board = [];
const SIZE = 3;  // 3x3 board
let currentPlayer = 'X';  // Starting player (Human)
const COMPUTER_PLAYER = 'O';  // Computer player

let humanScore = 0;
let computerScore = 0;

// Initialize the board
for (let i = 0; i < SIZE; i++) {
    board[i] = [];
    for (let j = 0; j < SIZE; j++) {
        board[i][j] = '';
    }
}

// Create the board cells
for (let i = 0; i < SIZE; i++) {
    for (let j = 0; j < SIZE; j++) {
        const cell = document.createElement('div');
        cell.className = 'cell';
        cell.dataset.row = i;
        cell.dataset.col = j;
        boardElement.appendChild(cell);
    }
}

// Event listener for cell clicks
boardElement.addEventListener('click', (event) => {
    const cell = event.target;
    if (cell.classList.contains('cell') && !cell.textContent && currentPlayer === 'X') {
        cell.textContent = currentPlayer;
        cell.classList.add(currentPlayer);
        board[cell.dataset.row][cell.dataset.col] = currentPlayer;
        const winningCells = checkWin(currentPlayer);
        if (winningCells.length > 0) {
            statusElement.textContent = `Player ${currentPlayer} wins!`;
            winningCells.forEach(([row, col]) => {
                const winningCell = document.querySelector(`.cell[data-row="${row}"][data-col="${col}"]`);
                winningCell.classList.add('winning-cell');
            });
            if (currentPlayer === 'X') {
                humanScore++;
                humanScoreElement.textContent = humanScore;
            } else {
                computerScore++;
                computerScoreElement.textContent = computerScore;
            }
            boardElement.removeEventListener('click', handleClick);  // Disable further clicks
        } else if (board.flat().every(cell => cell)) {
            statusElement.textContent = 'It\'s a draw!';
        } else {
            currentPlayer = COMPUTER_PLAYER;
            statusElement.textContent = `Computer's turn`;
            setTimeout(computerMove, 500);  // Delay computer move for better user experience
        }
    }
});

// Computer's move
function computerMove() {
    const bestMove = findBestMove();
    if (bestMove) {
        const [row, col] = bestMove;
        board[row][col] = COMPUTER_PLAYER;
        const cell = document.querySelector(`.cell[data-row="${row}"][data-col="${col}"]`);
        cell.textContent = COMPUTER_PLAYER;
        cell.classList.add(COMPUTER_PLAYER);

        const winningCells = checkWin(COMPUTER_PLAYER);
        if (winningCells.length > 0) {
            statusElement.textContent = `Computer wins!`;
            winningCells.forEach(([row, col]) => {
                const winningCell = document.querySelector(`.cell[data-row="${row}"][data-col="${col}"]`);
                winningCell.classList.add('winning-cell');
            });
            computerScore++;
            computerScoreElement.textContent = computerScore;
            boardElement.removeEventListener('click', handleClick);  // Disable further clicks
        } else if (board.flat().every(cell => cell)) {
            statusElement.textContent = 'It\'s a draw!';
        } else {
            currentPlayer = 'X';
            statusElement.textContent = `Player ${currentPlayer}'s turn`;
        }
    }
}

// Find the best move for the computer
function findBestMove() {
    let bestMove = null;
    let bestValue = -Infinity;

    for (let i = 0; i < SIZE; i++) {
        for (let j = 0; j < SIZE; j++) {
            if (board[i][j] === '') {
                board[i][j] = COMPUTER_PLAYER;
                const moveValue = minimax(board, 0, false);
                board[i][j] = '';
                if (moveValue > bestValue) {
                    bestValue = moveValue;
                    bestMove = [i, j];
                }
            }
        }
    }

    return bestMove;
}

// Minimax algorithm
function minimax(board, depth, isMaximizing) {
    const scores = { 'X': -10, 'O': 10, 'draw': 0 };
    const winner = checkWin('X').length > 0 ? 'X' : checkWin(COMPUTER_PLAYER).length > 0 ? COMPUTER_PLAYER : null;
    
    if (winner === COMPUTER_PLAYER) return scores[COMPUTER_PLAYER];
    if (winner === 'X') return scores['X'];
    if (board.flat().every(cell => cell)) return scores['draw'];

    if (isMaximizing) {
        let best = -Infinity;
        for (let i = 0; i < SIZE; i++) {
            for (let j = 0; j < SIZE; j++) {
                if (board[i][j] === '') {
                    board[i][j] = COMPUTER_PLAYER;
                    best = Math.max(best, minimax(board, depth + 1, false));
                    board[i][j] = '';
                }
            }
        }
        return best;
    } else {
        let best = Infinity;
        for (let i = 0; i < SIZE; i++) {
            for (let j = 0; j < SIZE; j++) {
                if (board[i][j] === '') {
                    board[i][j] = 'X';
                    best = Math.min(best, minimax(board, depth + 1, true));
                    board[i][j] = '';
                }
            }
        }
        return best;
    }
}

// Check for a win
function checkWin(player) {
    const winPatterns = [
        // Horizontal
        [[0, 0], [0, 1], [0, 2]],
        [[1, 0], [1, 1], [1, 2]],
        [[2, 0], [2, 1], [2, 2]],
        // Vertical
        [[0, 0], [1, 0], [2, 0]],
        [[0, 1], [1, 1], [2, 1]],
        [[0, 2], [1, 2], [2, 2]],
        // Diagonal
        [[0, 0], [1, 1], [2, 2]],
        [[0, 2], [1, 1], [2, 0]]
    ];

    for (const pattern of winPatterns) {
        if (pattern.every(([row, col]) => board[row][col] === player)) {
            return pattern;  // Return the winning cells
        }
    }
    return [];  // No winning cells
}

// Reset the game
resetButton.addEventListener('click', () => {
    boardElement.querySelectorAll('.cell').forEach(cell => {
        cell.textContent = '';
        cell.classList.remove('X', 'O', 'winning-cell');
    });
    for (let i = 0; i < SIZE; i++) {
        for (let j = 0; j < SIZE; j++) {
            board[i][j] = '';
        }
    }
    currentPlayer = 'X';
    statusElement.textContent = `Player ${currentPlayer}'s turn`;
    boardElement.addEventListener('click', handleClick);
});

// Handle logout button click
logoutButton.addEventListener('click', function() {
    window.location.href = '/logout';  // Redirect to /logout
});

// Handle save points button click
savePointsButton.addEventListener('click', () => {
    fetch('/save-points', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            'humanScore': humanScore,
            'computerScore': computerScore,
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Points saved successfully');
        } else {
            alert('Failed to save points');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to save points');
    });
});
