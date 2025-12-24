<?php

namespace App\Domain;
class Stage
{
    public function __construct(
        public string $label,
        public array $challenges = []
    ) {}

    public function addChallenge(Challenge $challenge): void
    {
        $this->challenges[] = $challenge;
    }
}
