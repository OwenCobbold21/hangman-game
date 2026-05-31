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
    } elseif (strlen($guess) === 1 && in_array($guess, $guessedLetters)) { // ** important to TEST
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
                // wrong letter - increment down 1 of the remianing guesses
                $_SESSION['remaining_guesses'] -= 1;
            }
        }

        // redirect to refresh page with the updated session
        header("Location: play.php");
        exit;
    }

    // update  variables  after validation
    $guessedLetters = $_SESSION['guessed_letters'];
    $remainingGuesses = $_SESSION['remaining_guesses'];

    // rebuild word display after guess
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
    $wordGuessed = !in_array('_', $wordDisplay);
}

$gameOver = false;
$gameWon = false;

if ($wordGuessed || $remainingGuesses <= 0) {
    $gameOver = true;
    $gameWon = $wordGuessed;

    // calculate score
    $lettersRevealed = count(array_filter($wordDisplay, fn($l) => $l !== '_'));
    $score = ($lettersRevealed * 5) + ($remainingGuesses * 10) + ($gameWon ? 15 : 0);
    $result = $gameWon ? 'Win' : 'Lose';

    // this block should only insert the score if it has nor already been inserte 
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

        $_SESSION['score_inserted'] = true;
    }
}

?>
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

    <?php if ($gameOver): ?>
        <?php if ($gameWon): ?>
            <h2>You Win!</h2>
            <p>You scored <strong><?php echo $score; ?></strong> points!</p>
        <?php else: ?>
            <h2>Game Over!</h2>
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