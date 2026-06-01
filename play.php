<?php
session_start();

// if no username or word is in the session  should take them back to login
if (!isset($_SESSION['username']) || !isset($_SESSION['word'])) {
    header("Location: start.php");
    exit;
}

// pull game data from current session into its own variables for easy play and readability
$word = $_SESSION['word'];
$difficulty = $_SESSION['difficulty'];
$guessedLetters = $_SESSION['guessed_letters'];
$remainingGuesses = $_SESSION['remaining_guesses'];
$username = $_SESSION['username'];
$errors = [];

// build the word display (_ _ _ a _ )
$wordDisplay = [];
$lettersRevealed = 0;

// Iterate through the users word guess, if the letter has been guessed, show if not show a blank (underscore)
foreach (str_split($word) as $letter) {
    if (in_array($letter, $guessedLetters)) {
        $wordDisplay[] = $letter;
        $lettersRevealed++;
    } else {
        $wordDisplay[] = '_';
    }
}

// check if word is fully revealed or not
$wordGuessed = !in_array('_', $wordDisplay);

if (isset($_POST['submit'])) {
    $guess = strtoupper(trim($_POST['guess'] ?? ''));

    if ($remainingGuesses <= 0 || $wordGuessed) {
        header("Location: play.php");
        exit;
    }
    // validation using regular expression 
    if (empty($guess)) {
        $errors['guess'] = "Please enter a guess.";
    } elseif (!preg_match('/^[A-Z]+$/', $guess)) { // check for non-apporpriate characters in the guess
        $errors['guess'] = "Your guess can only contain letters.";
    } elseif (strlen($guess) === 1 && in_array($guess, $guessedLetters)) {
        $errors['guess'] = "You already guessed that letter."; // make sure no reguessing is possible
    } else {

        // full word guess
        if (strlen($guess) > 1) {
            if ($guess === $word) {
                // correct word guess - reveal all letters
                $_SESSION['guessed_letters'] = str_split($word); // fixed  logic to increment full word guesses down only 1 instead of 2
            } else {
                // wrong word guess - decrement 1 guess
                $_SESSION['remaining_guesses'] -= 1;
            }
        } else {
            // single letter
            $_SESSION['guessed_letters'][] = $guess;

            if (!str_contains($word, $guess)) {
                // wrong letter - decrement remaining guesses by 1
                $_SESSION['remaining_guesses'] -= 1;
            }
        }
        // I think my code couldve been better here  but I don't wanna touch it now that its all working properly

        // redirect to refresh page with the updated session
        header("Location: play.php");
        exit;
    }

    // update variables after validation
    $guessedLetters = $_SESSION['guessed_letters'];
    $remainingGuesses = $_SESSION['remaining_guesses'];

    // rebuild the word display after guess
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
}

// game state variables
$gameOver = false;
$gameWon = false;
$score = 0;
$result = '';

if ($wordGuessed || $remainingGuesses <= 0) { // game loosing logic
    $gameOver = true;
    $gameWon = $wordGuessed; // game winning logic

    // count how many letters have been revealed (not blank)
    $lettersRevealed = count(array_filter($wordDisplay, fn($l) => $l !== '_'));
    // calculate score: letters revealed x 5 + remaining guesses x 10 + 15 bonus if win
    $score = ($lettersRevealed * 5) + ($remainingGuesses * 10) + ($gameWon ? 15 : 0);
    // ternary that sets the result to Win or Lose based on whether the word was guessed
    $result = $gameWon ? 'Win' : 'Lose';

    // only insert score if not already inserted
    if (!isset($_SESSION['score_inserted'])) {
        require_once './includes/library.php';
        $pdo = connectdb();

        $stmt = $pdo->prepare("
            INSERT INTO cois3430_assn1_scores 
            (username, difficulty, result, word, score, play_date)
            VALUES (:username, :difficulty, :result, :word, :score, NOW())
        ");
        $stmt->execute([
            ':username'   => $username,
            ':difficulty' => $difficulty,
            ':result'     => $result,
            ':word'       => $word,
            ':score'      => $score
        ]);

        $_SESSION['score_inserted'] = true; // Prevents duplicate score inserts
    }
}
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

        <?php if ($gameWon): ?>
            <?php if ($gameOver): ?>
                <h2 style="color: green">You Win!</h2>
                <p>You scored <strong><?php echo $score; ?></strong> points!</p>
            <?php else: ?>
                <h2 style="color: red">Game Over!</h2>
                <p>The word was: <strong><?php echo $word; ?></strong></p>
                <p>You scored <strong><?php echo $score; ?></strong> points!</p>
            <?php endif; ?>
            <div id="game-over-options">
                <a href="difficulty.php">Play Again</a>
                <a href="scores.php">See Other Scores</a>
            </div>
        <?php else: ?>
            <form method="post" novalidate>
                <div class="form-item">
                    <span class="error <?php echo isset($errors['guess']) ? '' : 'hidden'; ?>">
                        <?php echo $errors['guess'] ?? ''; ?>
                    </span>
                </div>
                <div class="form-item">
                    <label for="guess">Guess a letter or the whole word:</label>
                    <input type="text" id="guess" name="guess" maxlength="50">
                </div>
                <button type="submit" name="submit">Guess</button>
            </form>
        <?php endif; ?>

    </main>
    <footer>
        <p>&copy; Owen Cobbold 2026</p>
    </footer>
</body>

</html>