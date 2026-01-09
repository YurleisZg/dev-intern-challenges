<?php
namespace  App\Domain;
class Challenge
{
    public function __construct(
        public string $name,
        public string $description,
        public string $routeName
    )
    {
    }
}

