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

if (isset($_POST['submit'])) {
    $guess = strtoupper(trim($_POST['guess'] ?? ''));

    // validation
    if (empty($guess)) {
        $errors['guess'] = "Please enter a guess.";
    } elseif (!preg_match('/^[A-Z]+$/', $guess)) {
        $errors['guess'] = "Your guess can only contain letters.";
    } elseif (strlen($guess) === 1 && in_array($guess, $guessedLetters)) {
        $errors['guess'] = "You already guessed that letter.";
    } else {

        // full word guess
        if (strlen($guess) > 1) {
            if ($guess === $word) {
                // correct word guess - reveal all letters
                $_SESSION['guessed_letters'] = str_split($word);
                $_SESSION['remaining_guesses'] = $remainingGuesses;
            } else {
                // wrong word guess - minus 2 guesses
                $_SESSION['remaining_guesses'] -= 2;
            }
        } else {
            // single letter 
            $_SESSION['guessed_letters'][] = $guess;

            if (!str_contains($word, $guess)) {
                // wrong letter - minus 1 guess
                $_SESSION['remaining_guesses'] -= 1;
            }
        }

        // redirect to refresh page with the updated session
        header("Location: play.php");
        exit;
    }

//     // update  variables  after validation
//     $guessedLetters = $_SESSION['guessed_letters'];
//     $remainingGuesses = $_SESSION['remaining_guesses'];

//     // rebuild word display after guess
//     $wordDisplay = []
//     $lettersRevealed = 0;
//     foreach (str_split($word) as $letter) {
//         if (in_array($letter, $guessedLetters)) {
//    

//  logic that detects win/loss and scores properly



// show win  or loss on UI

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