<?php

namespace App\Domain;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity()]
#[ORM\Table(name: 'answers')]
final class Answer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isCorrect;

    #[ManyToOne(targetEntity: Question::class, inversedBy: 'answers')]
    #[JoinColumn(name: 'question_id', referencedColumnName: 'id')]
    private ?Question $question = null;

    public function __construct(string $title, bool $isCorrect)
    {
        $this->title = $title;
        $this->isCorrect = $isCorrect;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function setQuestion(?Question $question): void
    {
        $this->question = $question;
    }

}
