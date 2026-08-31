<?php

namespace App\TaskManager\Domain\Task;

use App\TaskManager\Domain\Task\Event\AssignUser;
use App\TaskManager\Domain\Task\Event\TaskCreated;
use App\TaskManager\Domain\Task\Event\TaskStatusChanged;
use App\TaskManager\Domain\User\User;
use DateTimeImmutable;

class Task
{
    private string $id;
    private string $name;
    private string $description;
    private User $assignedUser;
    private TaskStatus $status;
    private array $recordedEvents;

    public static function create(
        string $id, string $name, string $description, User $assignedUser): self
    {
        $task = new self();
        $task->apply(new TaskCreated($id, $name, $description, $assignedUser, new DateTimeImmutable()));
        return $task;
    }

    public function changeStatus(TaskStatus $Status): void
    {
        if ($this->status !== $Status) {
            $this->apply(new TaskStatusChanged($this->id, $Status, new DateTimeImmutable()));
        }
    }

    public function assignUser(User $assignedUser)
    {
        if ($this->assignedUser->getId() !== $assignedUser->getId()) {
            $this->apply(new AssignUser($this->id, $assignedUser, new DateTimeImmutable()));
        }
    }


//    public static function reconstituteFromEvents(array $events): self
//    {
//        $task = new self();
//        foreach ($events as $event) {
//            $task->handle($event);
//        }
//        return $task;
//    }

    private function apply($event): void
    {
        $this->recordedEvents[] = $event;
        $this->handle($event);
    }

    private function handle($event): void
    {
        if ($event instanceof TaskCreated) {
            $this->id = $event->id;
            $this->name = $event->name;
            $this->description = $event->description;
            $this->assignedUser = $event->assignedUser;
            $this->status = TaskStatus::TO_DO;
        } elseif ($event instanceof TaskStatusChanged) {
            $this->status = $event->status;
        } elseif ($event instanceof AssignUser) {
            $this->assignedUser = $event->assignedUser;
        }
    }

    // Getters
    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getDescription(): string { return $this->description; }
    public function getAssignedUserId(): string { return $this->assignedUser->getId(); }
    public function getStatus(): TaskStatus { return $this->status; }
}
