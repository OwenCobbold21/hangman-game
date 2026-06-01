
<?php
// Owen Cobbold - 0895910

session_start();

$username = $_POST['username'] ?? '';
$errors = [];
$errorMessage = ''; // initialize errormessage

if (isset($_POST['submit'])) {

    // check if empty
    if (empty($username)) {
        $errors['username'] = true;
        $errorMessage = "Please enter a username.";

        // check if username contains valid characters, only letters, numbers, underscores
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors['username'] = true;
        $errorMessage = "Username can only contain letters, numbers, and underscores.";
    }

    // if no errors store in session and redirect
    if (empty($errors)) {
        $_SESSION['username'] = $username;
        header("Location: difficulty.php");
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
                    value="<?php echo htmlspecialchars($username); ?>"> 
                <span class="error <?php echo !isset($errors['username']) ? 'hidden' : ''; ?>">
                    <?php echo $errorMessage; ?>
                </span>
            </div>
            <button type="submit" name="submit">Start Game</button>
        </form>
    </main>
    <footer>
        <p>&copy; Owen Cobbold 2026</p>
    </footer>
</body>

</html>