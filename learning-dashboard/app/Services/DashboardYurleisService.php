<?php


namespace App\Services;

use App\Domain\Stage;
use App\Domain\Challenge;

class DashboardYurleisService
{
    public function getRoadmap(): array
    {
        $stages = new Stage('Challenges');
        $stages->addChallenge(
            new Challenge('Salary Calculator', 'Salary calculator with overtime support', 'yurleis.challenges.salary-calculator.'),
        );
        $stages->addChallenge(
            new Challenge('Fruit Logic', 'An interactive mini-game with two fruit baskets (Basket A and Basket B)', 'yurleis.challenges.auth.'),
        );
        $stages->addChallenge(
            new Challenge('Toggle Time Attack', 'Game where you quickly switch elements on and off before time runs out', 'yurleis.challenges.auth.')
        );

        return [
            $stages,
        ];
    }
}

