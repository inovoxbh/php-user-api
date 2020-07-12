<?php

namespace App\Message;

use Symfony\Component\HttpFoundation\Request;

final class CreateUserMessage
{
    private string $name;
    private string $email;
    private array $telephones;

    public function __construct(String $name, String $email, Array $telephones)
    {
        $this->name = $name;
        $this->email = $email;
        $this->telephones = $telephones;
    }

    public function getName(): String
    {
        return $this->name;
    }

    public function getEmail(): String
    {
        return $this->email;
    }

    public function getTelephones(): Array
    {
        return $this->telephones;
    }
}
