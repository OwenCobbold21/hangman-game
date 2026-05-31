<?php
session_start();

// if no username is in the session this should send them back to the start
if (!isset($_SESSION['username'])) {
    header("Location: start.php");
    exit;
}

$difficulty = $_POST['difficulty'] ?? '';
$errors = [];

if (isset($_POST['submit'])) {

    // validate difficulty is selected
    if (empty($difficulty)) {
        $errors['difficulty'] = true;
    }
}

// 


//

?>

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hangman - Select Difficulty</title>
    <link rel="stylesheet" href="./styles/main.css">
</head>
<body>
    <header>
        <h1>Hangman</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    </header>
    <main>
        <h2>Select Difficulty</h2>
        <form method="post" novalidate>
            <div class="form-item">
                <span class="error <?php echo !isset($errors['difficulty']) ? 'hidden' : ''; ?>">
                    Please select a difficulty level.
                </span>
            </div>
            <div class="form-item row">
                <input type="radio" id="easy" name="difficulty" value="Easy"
                    <?php echo ($difficulty === 'Easy') ? 'checked' : ''; ?> />
                <label for="easy">Easy (3-5 letter words)</label>
            </div>
            <div class="form-item row">
                <input type="radio" id="medium" name="difficulty" value="Medium"
                    <?php echo ($difficulty === 'Medium') ? 'checked' : ''; ?> />
                <label for="medium">Medium (6-8 letter words)</label>
            </div>
            <div class="form-item row">
                <input type="radio" id="hard" name="difficulty" value="Hard"
                    <?php echo ($difficulty === 'Hard') ? 'checked' : ''; ?> />
                <label for="hard">Hard (9+ letter words)</label>
            </div>
            <button type="submit" name="submit">Start Game</button>
        </form>
    </main>
    <footer>
        <p>&copy; Owen Cobbold 2024</p>
    </footer>
</body>
</html>