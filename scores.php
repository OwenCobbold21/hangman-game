<?php
session_start();

// if no username is in session send them back to start
if (!isset($_SESSION['username'])) {
    header("Location: start.php");
    exit;
}

require_once './includes/library.php';
$pdo = connectdb();

// prepare the 10 most recent scores for each difficulty
$easyScores = $pdo->prepare("
    SELECT username, result, word, score, play_date
    FROM cois3430_assn1_scores
    WHERE difficulty = 'Easy'
    ORDER BY play_date DESC
    LIMIT 10
");
$easyScores->execute();
$easyResults = $easyScores->fetchAll();

$mediumScores = $pdo->prepare("
    SELECT username, result, word, score, play_date
    FROM cois3430_assn1_scores
    WHERE difficulty = 'Medium'
    ORDER BY play_date DESC
    LIMIT 10
");
$mediumScores->execute();
$mediumResults = $mediumScores->fetchAll();

$hardScores = $pdo->prepare("
    SELECT username, result, word, score, play_date
    FROM cois3430_assn1_scores
    WHERE difficulty = 'Hard'
    ORDER BY play_date DESC
    LIMIT 10
");
$hardScores->execute();
$hardResults = $hardScores->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hangman - Scores</title>
    <link rel="stylesheet" href="./styles/main.css">
</head>

<body>
    <header>
        <h1>Hangman</h1>
    </header>
    <main>
        <h2>Score Tables</h2>

        <h3>Easy</h3>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Result</th>
                    <th>Word</th>
                    <th>Score</th>
                    <th>Date Played</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($easyResults)): ?>
                    <tr>
                        <td colspan="5">No scores yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($easyResults as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo $row['result']; ?></td>
                            <td><?php echo $row['word']; ?></td>
                            <td><?php echo $row['score']; ?></td>
                            <td><?php echo $row['play_date']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>Medium</h3>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Result</th>
                    <th>Word</th>
                    <th>Score</th>
                    <th>Date Played</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($mediumResults)): ?>
                    <tr>
                        <td colspan="5">No scores yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($mediumResults as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo $row['result']; ?></td>
                            <td><?php echo $row['word']; ?></td>
                            <td><?php echo $row['score']; ?></td>
                            <td><?php echo $row['play_date']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>Hard</h3>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Result</th>
                    <th>Word</th>
                    <th>Score</th>
                    <th>Date Played</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($hardResults)): ?>
                    <tr>
                        <td colspan="5">No scores yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($hardResults as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo $row['result']; ?></td>
                            <td><?php echo $row['word']; ?></td>
                            <td><?php echo $row['score']; ?></td>
                            <td><?php echo $row['play_date']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="difficulty.php">Play Again</a>

    </main>
    <footer>
        <p>&copy; Owen Cobbold 2026</p>
    </footer>
</body>

</html>