<?php

namespace App\Message;

use Symfony\Component\Validator\Constraints as Assert;

final class UpdateUserMessage
{
    /**
     * @Assert\NotBlank(message="ID obrigatório (atualização).")
     */
    private int $id;

    /**
     * @Assert\NotBlank(message="Nome obrigatório (atualização).")
     * @Assert\Length(
     *     min="5",
     *     minMessage="Nome deve conter no mínimo {{ limit }} caracteres (atualização).",
     *     max="10",
     *     maxMessage="Nome deve conter no máximo {{ limit }} caracteres (atualização)."
     * )
     */
    private string $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private string $email;

    public function __construct(int $id, string $name, string $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
