<?php
declare(strict_types=1);
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
final class Telephone
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    private int $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private User $user;
    /**
     * @ORM\Column(type="string")
     */
    private string $number;
    public function __construct(string $number, User $user)
    {
        $this->number = $number;
        $this->user = $user;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getNumber(): string
    {
        return $this->number;
    }
}