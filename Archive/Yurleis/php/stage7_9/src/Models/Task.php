<?php
namespace App\Models;

use DateTime;

class Task
{
    private int $id;
    private string $title;
    private string $description;
    private ?DateTime $created_at;
    private ?DateTime $finish_on;
    private int $status_id;

    private int $user_id;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCreated_at(): ?DateTime
    {
        return $this->created_at;
    }

    public function setCreated_at(?DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getFinish_on(): ?DateTime
    {
        return $this->finish_on;
    }

    public function setFinish_on(?DateTime $finish_on): void
    {
        $this->finish_on = $finish_on;
    }

    public function getStatus_id(): int
    {
        return $this->status_id;
    }

    public function setStatus_id(int $status_id): void
    {
        $this->status_id = $status_id;
    }

    public function getUser_id(): int
    {
        return $this->user_id;
    }

    public function setUser_id(int $user_id): void
    {
        $this->user_id = $user_id;
    }


}

?>