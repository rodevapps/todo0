<?php

namespace App\Entity;

use App\Repository\TodoItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TodoItemRepository::class)]
class TodoItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $done = null;

    #[ORM\ManyToOne(inversedBy: 'todoItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TodoList $todo_list_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isDone(): ?bool
    {
        return $this->done;
    }

    public function setDone(bool $done): self
    {
        $this->done = $done;

        return $this;
    }

    public function getTodoListId(): ?TodoList
    {
        return $this->todo_list_id;
    }

    public function setTodoListId(?TodoList $todo_list_id): self
    {
        $this->todo_list_id = $todo_list_id;

        return $this;
    }
}
