<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Dashboard</title>
    <style>
        /* Base layout */
        body {
            margin: 0;
            padding: 0;
            background: #fafafa;
            font-family: 'Segoe UI', sans-serif;
            color: #2d2d2d;
        }

        header {
            padding: 40px 20px;
            text-align: center;
        }

        h1 {
            font-size: 2.2rem;
            margin-bottom: 10px;
            font-weight: 600;
        }

        p.subtitle {
            color: #666;
            font-size: 1rem;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 0 auto 60px;
        }

        /* Week section */
        .week {
            margin-top: 40px;
        }

        .week h2 {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 18px;
        }

        /* Exercise cards */
        .exercise {
            border: 1px solid #e6e6e6;
            padding: 14px 18px;
            border-radius: 6px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            transition: 0.15s;
        }

        .exercise:hover {
            border-color: #d6d6d6;
        }

        .exercise-title {
            font-weight: 500;
            font-size: 1rem;
        }

        .exercise a {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 4px;
            border: 1px solid #d0d0d0;
            font-size: .9rem;
            color: #444;
            transition: .2s;
        }

        .exercise a:hover {
            background: #f2f2f2;
        }
    </style>
</head>

<body>
<header>
    <h1>Developer Training Dashboard</h1>
    <p class="subtitle">Track the weekly challenges and coding progress</p>
</header>

<div class="container">

    
    <section class="week">
        <h2>Stage 0 to 5</h2>

        <div class="exercise">
            <span class="exercise-title">Challenge 1 — Salary Calculator</span>
            <a href="FirstChallenge/challenge1.php">Open</a>
        </div>
        <div class="exercise">
            <span class="exercise-title">Challenge 2 — The fruit set logic</span>
            <a href="SecondChallenge/viewFruitSet.php">Open</a>
        </div>
        <div class="exercise">
            <span class="exercise-title">Challenge 3 — The toggle time attack </span>
            <a href="./ThirdChallenge/challenge3.php">Open</a>
        </div>

    </section>


    <section class="week">
        <h2>Stage 6 to 9</h2>
        <div class="exercise">
            <span class="exercise-title">To Do List </span>
            <a href="/Elkin/stage6/public/index.php">Open</a>
        </div>
    </section>

</div>
</body>
</html>
