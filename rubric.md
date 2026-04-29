# COIS 3430 2025FA Assignment #1 Rubric

## Game Play

| **Criteria**                     | **Description**                                                                                                                                   | **Points** |
| -------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------- | ---------- |
| **Username Collection**       | Form correctly collects a username and stores it in a session; game does not start without a valid username.                                      | 3          |
| **Difficulty Selection**      | Form allows the user to select Easy, Medium, or Hard; game board displays only after selection.                                                   | 3          |
| **Database Word Bank**        | Words are retrieved from the `words` table according to the selected difficulty; a random word is selected for the game.                          | 4          |
| **Game Display**              | Game state displays masked word, letters guessed, remaining guesses, difficulty level, and username.                                              | 5          |
| **User Input Validation**     | Proper validation for single-letter and full-word guesses; invalid inputs are handled with error messages.                                        | 3          |
| **Guess Evaluation**          | Single-letter guesses reveal letters correctly or decrement guesses; full-word guesses work as specified (correct → win, incorrect → -2 guesses). | 4          |
| **End of Game**               | Correctly detects win/loss conditions; displays “You Win” or “Game Over” and reveals the word.                                                    | 3          |
| **Score Calculation**         | Score is correctly calculated based on formula: (letters revealed × 5) + (remaining guesses × 10) + (15 for win).                                                | 3          |
| **Database Score Storage**    | After each game, inserts a row into `scores` with username, difficulty, result, word, and score.                                                  | 3          |
| **Display Score Tables**     | Displays three separate tables (Easy, Medium, Hard) showing all scores for each difficulty.                                                       | 2          |
| **Play Again Functionality** | “Play Again” button returns user to difficulty selection without clearing username or database scores.                                            | 2          |
| **Bonus** | Visual Display of Hangman                                            | 3          |

## Overall Assignment Design, Implementation and Usability

| **Criteria**                                                       | **Marks** |
| ------------------------------------------------------------------ | --------- |
| Design: Basic layout is professional, user-friendly and functional | 5         |
| Code Quality (clean, well-structured, and follows best practices)  | 3         |
| Documentation (clear, concise comments, without being over done)   | 3         |
| Testing (different inputs, sample outputs, HTML validation)        | 3         |
| Good Git Habits (atomic commits, good commit messages)             | 3         |
