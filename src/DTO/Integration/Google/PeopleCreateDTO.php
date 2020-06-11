<?php

declare(strict_types=1);

namespace App\DTO\Integration\Google;

class PeopleCreateDTO
{
    private string $name;

    private ?string $email = null;

    private ?string $phone = null;

    public function __construct(string $name, string $email = null, string $phone = null)
    {
        $this->setName($name);
        $this->setEmail($email);
        $this->setPhone($phone);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }
}
