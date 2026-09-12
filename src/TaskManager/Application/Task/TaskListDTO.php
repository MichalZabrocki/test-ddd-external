<?php

namespace App\TaskManager\Application\Task;

use App\Shared\Domain\Bus\Query\Response;

use Iterator;

class TaskListDTO implements Response, Iterator
{
    private $position = 0;
    /**
     * @param TaskDTO[] $tasks
     */
    public function __construct(
        public array $tasks
    ){}

    public static function fromList(array $list): TaskListDTO
    {
        foreach ($list as $task) {
            if (!$task instanceof TaskDTO) {
                throw new \InvalidArgumentException('Invalid task DTO');
            }
        }

        return new self(
            $list
        );
    }

    public function current(): mixed
    {
        return $this->tasks[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->tasks[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}
