<?php
session_start();

// if no username or word is in the session send them back to login
if (!isset($_SESSION['username']) || !isset($_SESSION['word'])) {
    header("Location: start.php");
    exit;
}

// pull game data from current session into variables for easy use
$word = $_SESSION['word'];
$difficulty = $_SESSION['difficulty'];
$guessedLetters = $_SESSION['guessed_letters'];
$remainingGuesses = $_SESSION['remaining_guesses'];
$username = $_SESSION['username'];
$errors = [];

// build the word display (_ _ _ a _ )
$wordDisplay = [];
$lettersRevealed = 0;

foreach (str_split($word) as $letter) {
    if (in_array($letter, $guessedLetters)) {
        $wordDisplay[] = $letter;
        $lettersRevealed++;
    } else {
        $wordDisplay[] = '_';
    }
}

// check if word is fully revealed
$wordGuessed = !in_array('_', $wordDisplay);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hangman - Play</title>
    <link rel="stylesheet" href="./styles/main.css">
</head>

<body>
    <header>
        <h1>Hangman</h1>
    </header>
    <main>
        <div id="game-info">
            <p>Player: <strong><?php echo htmlspecialchars($username); ?></strong></p>
            <p>Difficulty: <strong><?php echo $difficulty; ?></strong></p>
            <p>Remaining Guesses: <strong><?php echo $remainingGuesses; ?></strong></p>
        </div>

        <div id="word-display">
            <?php foreach ($wordDisplay as $letter): ?>
                <span class="letter"><?php echo $letter; ?></span>
            <?php endforeach; ?>
        </div>

        <div id="guessed-letters">
            <p>Guessed Letters:
                <strong>
                    <?php echo !empty($guessedLetters) ? implode(', ', $guessedLetters) : 'None yet'; ?>
                </strong>
            </p>
        </div>

        <form method="post" novalidate>
            <div class="form-item">
                <span class="error <?php echo isset($errors['guess']) ? '' : 'hidden'; ?>">
                    <?php echo $errors['guess'] ?? ''; ?>
                </span>
            </div>
            <div class="form-item">
                <label for="guess">Guess a letter or the whole word:</label>
                <input type="text" id="guess" name="guess" maxlength="50" />
            </div>
            <button type="submit" name="submit">Guess</button>
        </form>
    </main>
    <footer>
        <p>&copy; Owen Cobbold 2026</p>
    </footer>
</body>

</html>