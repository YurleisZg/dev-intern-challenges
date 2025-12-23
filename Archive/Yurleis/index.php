<?php

$projects = [
    'First Week' => [
        [
            'title' => 'PHP Accountant',
            'description' => 'Salary calculator with overtime support',
            'link' => 'php/challenges/accountant/index.php'
        ],
        [
            'title' => 'Fruit Set',
            'description' => 'An interactive mini-game with two fruit baskets (Basket A and Basket B)',
            'link' => 'php/challenges/fruit_set/fruit_set.php'
        ],
             [
            'title' => 'The "Toggle" Time-Attack',
            'description' => 'Game where you quickly switch elements on and off before time runs out',
            'link' => 'php/challenges/toggle_time/start.php'
        ],
    ],

    'Second Week' => [
        [
            'title' => 'To Do',
            'description' => 'A simple To-Do list application to manage tasks',
            'link' => 'php/stage7_9/public/dashboard.php'
        ],
        [
            'title' => 'Challenges Updated',
            'description' => 'Updated challenges that now allow saving and editing reports',
            'link' => 'php/challenges-updated/public/index.php'
        ]
        ],
];
?>

<!DOCTYPE html>
<html>

<head>
    <title>challenges</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1 class="title">Welcome to, Dashboard challenges</h1>
        <span class="description"> You can navigate for diferents challenges and projects.</span>
    </header>
    <section class="section-container">
        <h2 class="subtitle">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
            class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-week" style="vertical-align: middle;"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
            <path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M7 14h.013" /><path d="M10.01 14h.005" /><path d="M13.01 14h.005" />
            <path d="M16.015 14h.005" /><path d="M13.015 17h.005" /><path d="M7.01 17h.005" /><path d="M10.01 17h.005" />
            </svg> Challenges
        </h2>
        <?php foreach ($projects as $groupName => $groupProjects): ?>
            <div class="acordion-container">
                <details name="challenges">
                <summary class="summary">
                    <?php echo htmlspecialchars($groupName); ?>
                </summary>

                <div class="cards-container">
                    <?php foreach ($groupProjects as $project): ?>
                        <div class="card">
                           <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                        <p><?php echo htmlspecialchars($project['description']); ?></p>
                        <a class="icon-class" href="<?php echo htmlspecialchars($project['link']); ?>" class="card-link">
                            View
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                            </svg>
                        </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </details>
            </div>
        <?php endforeach; ?>
    </section>
    </details>
    </section>

    <footer>
        <p>&copy; 2025 Yurleis. All rights reserved.</p>
    </footer>
</body>
</html>