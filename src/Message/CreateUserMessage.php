<?php

namespace App\Message;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateUserMessage
{
    /**
     * @Assert\NotBlank(message="Nome obrigatorio asdf")
     * @Assert\Length(
     *     min="5",
     *     minMessage="Nome pelo menos {{ limit }} caracteres asdf.",
     *     max="10",
     *     maxMessage="Nome no máximo {{ limit }} caracteres fdsa."
     * )
     */
    private string $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private string $email;

    /**
     * @Assert\Count(min="2")
     * @Assert\Valid()
     */
    private array $telephones;

    public function __construct(string $name, string $email, array $telephones)
    {
        $this->name = $name;
        $this->email = $email;
        $this->telephones = $telephones;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getTelephones(): array
    {
        return $this->telephones;
    }
}
