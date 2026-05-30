<?php
session_start();

$username = $_POST['username'] ?? '';
$errors = [];

if (isset($_POST['submit'])) {

    // validate that the username isn't empty
    if (empty($username)) {
        $errors['username'] = true;
    }

    // if no errors then store in session and redirect to the game
    if (empty($errors)) {
        $_SESSION['username'] = $username;
        header("Location: game.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hangman - Enter Username</title>
    <link rel="stylesheet" href="./styles/main.css">
</head>
<body>
    <header>
        <h1>Hangman</h1>
    </header>
    <main>
        <h2>Enter Your Username</h2>
        <form method="post" novalidate>
            <div class="form-item">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username"
                    value="<?php echo htmlspecialchars($username); ?>" />
                <span class="error <?php echo !isset($errors['username']) ? 'hidden' : ''; ?>">
                    Please enter a username to continue.
                </span>
            </div>
            <button type="submit" name="submit">Start Game</button>
        </form>
    </main>
</body>
</html>