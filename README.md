[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-22041afd0340ce965d47ae6ef1cefeee28c7c493a6346c4f15d667ab976d596c.svg)](https://classroom.github.com/a/8-DtPHKz)
# Assignment #1 - Hangman <!-- omit in toc -->

- [General Information](#general-information)
- [Partner Work](#partner-work)
- [Problem Summary](#problem-summary)
- [Database Tables](#database-tables)
- [Game Play](#game-play)
  - [1. Username Collection (`start.php`)](#1-username-collection-startphp)
  - [2. Difficulty Selection (`difficulty.php`)](#2-difficulty-selection-difficultyphp)
  - [3. Game Play (`play.php`)](#3-game-play-playphp)
  - [4. Guess Evaluation (`play.php`)](#4-guess-evaluation-playphp)
  - [5. End of Game (`play.php`)](#5-end-of-game-playphp)
  - [6. Display Score Tables (`scores.php`)](#6-display-score-tables-scoresphp)
- [Bonus](#bonus)
- [Testing](#testing)
- [Submission](#submission)

## General Information

- All assignments must be completed in GitHub Classroom.
- Although HTML and CSS are not the *point* of this course, they did not stop being important when we moved on to new things. You are expected to generate valid, sematic HTML, and you must do enough styling to make your solutions look nice and professional.
- You cannot use any external libraries or frameworks, and you solution must not include any JavaScript.
- For the purposes of testing your PHP validation, you must not use any HTML5 form validation.  If you include form elements with built-in validation, your form tag should include a `novalidate` attribute.
- The point of an assignment is to illustrate your understanding the concepts taught in class. Penalties will be applied for solutions that don't use the techniques taught.

>[!TIP]
>Besides doing it for marks, if you invest a little extra time in an improved appearance and good UX, you'll have something you can use as a portfolio piece.

> [!CAUTION]
> **Do not fork the repository that gets created for you when you accept the assignment.** GitHub Classroom has already forked it for you! If you do so again, we won't be able to mark it.

> [!CAUTION]
> **Students who directly include their database credentials in any file in their repository will automatically receive a failing grade.**

## Partner Work

- To start off, just a quick reminder that you can choose to work on any (or all) assignment(s) with a partner. Although I strongly encourage partner work for Assignments 2 and 3 (they are basically a project split in half), this assignment is standalone and is certainly doable by yourself.

- This assignment isn't particularly well suited to splitting up if *divide and conquer* is how you typically approach partner work. If you want to do proper *pair programming,* where you actually work together, so that you have two problem solvers/debuggers, that would be a useful way to do partner work on this one.

- If you are going to work with a partner, one person should accept the GitHub Classroom invitation, and then invite the other to their git repository (you don't both need to create different repos).

- Only one of you should submit the necessary links on Blackboard, letting me know which repo to mark, and where to access it on Loki. You must also include the name of your partner. The other person should just submit the name of their partner (I need you to submit something, so I can give you the feedback).

- If you're working with a partner, you will both need to create the database tables in your database.

---

## Problem Summary

In this assignment, you will build a **text-based Hangman game** using PHP. The goal is to practice **arrays**, **sessions**, **conditional logic**, **functions**, **form handling**, and **database access with MySQL**. Your game will include **difficulty levels**, **username collection**, **letter and full-word guessing**, and a **database-based scoring system**. The word bank will also be stored in a **pre-populated database table**, which your application must query

---

## Database Tables

- The words for the game are stored in a database table named `cois3430_assn1_words`.
  - When you create the table, you must **pre-populate** it with at least 10 words per difficulty level.

  **Table structure:**

  | Field Name | Type        | Description                          |
  | ---------- | ----------- | ------------------------------------ |
  | id         | INT (PK)    | Unique identifier                    |
  | word       | VARCHAR(50) | Word used in the Hangman game        |
  | difficulty | ENUM        | Difficulty level: Easy, Medium, Hard |

- Game scores will be stored in a table named `cois3430_assn1_scores`

  **Table structure:**

  | Field Name | Type        | Description                        |
  | ---------- | ----------- | ---------------------------------- |
  | id         | INT (PK)    | Unique identifier                  |
  | username   | VARCHAR(50) | Player’s username                  |
  | difficulty | VARCHAR(10) | Easy, Medium, or Hard              |
  | result     | VARCHAR(10) | Win or Lose                        |
  | word       | VARCHAR(50) | Word used in the game              |
  | score      | INT         | Score calculated using the formula |
  | play_date  | DATETIME    | The date/time stamp of game play   |

---

## Game Play

### 1. Username Collection (`start.php`)

When the user first visits the application, display a form asking for a **username**.

- Store the username in a **PHP session** so it can be reused for all subsequent games.
- The game cannot start until a valid username is provided.

### 2. Difficulty Selection (`difficulty.php`)

After the username is stored, redirect to a form allowing the user to select a **difficulty level**:

- **Easy** (3–5 letter words)
- **Medium** (6–8 letter words)
- **Hard** (9+ letter words)

When the user selects a difficulty, query the database for words with that difficulty and **randomly select one** for the game.

> [!TIP]
> You can select a random row with PHP or using `ORDER BY RAND() LIMIT 1` in SQL.

### 3. Game Play (`play.php`)

While the user is playing, in a nicely laid out game board, show:

- The current state of the word (e.g., `_ _ A _ _`)
- Letters the user has already guessed
- Remaining number of guesses
- Selected difficulty level
- Current username

Provide a single HTML form that allows the user to:

- Guess a **single letter**, or
- Guess the **entire word**

Validate input and display an error if:

- The guess is empty
- Input contains numbers or symbols
- A letter guess contains **more than one character**
- The letter has already been guessed

>[!Note]
Game play will require you to store several other things in session. Your logic implementation and design decisions will influence what these will be.

### 4. Guess Evaluation (`play.php`)

On submission, the self-processing game play page must also evaluate the user's guess:

- **Single letter guess:**

  - Reveal all matching letters if correct
  - Decrement remaining guesses by 1 if incorrect

- **Full word guess:**

  - If correct → immediately win the game
  - If incorrect → decrement remaining guesses by 2

### 5. End of Game (`play.php`)

After evaluating the user's guess, *game play* might also need to handle a win or loss:

- If all letters are revealed (though word guess, or last letter guess), display **“You Win”**
- If the remaining guesses reach 0, display **“Game Over”** and reveal the correct word
- You should also give the user the options to:
  - **"Play Again"**: should reset the game and take the user back to the difficulty page.
  - **"See Other Scores"**: take the user to the scores page below

After the game ends, calculate the score as:

  ```
  Score = (Number of letters revealed × 5) + (Remaining guesses × 10) + (15 for winning - if they won)
  ```

Insert a new row with the username, difficulty, result, word, and score in the scores table.

### 6. Display Score Tables (`scores.php`)

After inserting the score, query the `scores` table and display **three separate tables**, one for each difficulty, which should include the following score data:

| Username | Result | Word | Score | Date Played |
| -------- | ------ | ---- |------ |------------ |
| | | | | |

Each table should include the 10 most recent scores in the database for that difficulty level.

---

## Bonus

Make the game a properly visual hangman using ascii art or dynamically displyed images.

---

## Testing

- Your testing document should start with a clickable link (using correct Markdown syntax) to the live version of that question on Loki.
  
- Most of what you're doing on this assignment is browser independent since it's happening on the server. This means your testing document doesn't need to include cross-browser testing. However, it is probably a good to test for your own sake that none of your CSS/HTML is broken cross-browser/cross-platform, since, if it appears broken when I test it, I will deduct marks unless you've warned me to expect an issue.
- You are still expected to validate the HTML you generate. You will need to do it after the page has loaded in the browser (since the validator will fail on any embedded PHP). Pages without authentication/session can be validated by providing the Loki link to the W3C validator. For pages with authentication/session, you'll need to copy the source of the page into direct input.

---

## Submission

- The Blackboard submission of your assignment must include a link to your GitHub repo, and a link to the live version of your question on Loki (two links in total). Although we can see the repo in GitHub Classroom without the link, the submission on Blackboard is what tells me you are ready for it to be marked. If there is no Blackboard submission, your assignment will not be marked.

- You are responsible for ensuring that everything you want to be marked as been merged into the main branch of your repo, and that you have correctly pushed everything to the remote version of your repo.
- You must also verify that all your testing images are visible in the testing markdown document in the remote repository!
- Penalties will be applied if submission in not completed correctly, so be sure to double check your repo, your testing file, and your Blackboard submission.

---
