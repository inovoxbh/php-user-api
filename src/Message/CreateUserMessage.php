<?php

namespace App\Message;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateUserMessage
{
    /**
     * @Assert\NotBlank(message="Nome obrigatório (inserção).")
     * @Assert\Length(
     *     min="5",
     *     minMessage="Nome deve conter no mínimo {{ limit }} caracteres (inserção).",
     *     max="10",
     *     maxMessage="Nome deve conter no máximo {{ limit }} caracteres (inserção)."
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
