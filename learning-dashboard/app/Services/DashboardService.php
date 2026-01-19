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
            new Challenge('Challenge 1', 'Salary Calculator', 'elkin.challenges.salary-calculator.index'),
        );
        $stage0to5->addChallenge(
            new Challenge('Challenge 2', 'Fruit Logic', 'elkin.challenges.fruit-set-logic.index'),
        );

        return [
            $stage0to5,
        ];
    }
}

