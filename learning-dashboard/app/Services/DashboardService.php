<?php

namespace App\Services;

use App\Domain\Stage;
use App\Domain\Challenge;

class DashboardService
{
    public function getRoadmap(): array
    {
        $stage0to5 = new Stage('Stage 0 to 5');
        $stage0to5->addChallenge(
            new Challenge('Challenge 1', 'Salary Calculator', 'challenges.salary-calculator.'),
        );
        $stage0to5->addChallenge(
            new Challenge('Challenge 2', 'Fruit Logic', 'challenges.fruit-set-logic.'),
        );
        $stage0to5->addChallenge(
            new Challenge('Challenge 3', 'Toggle Time Attack', 'challenges.toogle-time-attack.')
        );

        $stage6to9 = new Stage('Stage 6 to 9');
        $stage6to9->addChallenge(
            new Challenge('To Do List', '', 'challenges.to-do-list.'),
        );

        return [
            $stage0to5,
            $stage6to9,
        ];
    }
}

