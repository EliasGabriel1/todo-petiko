<?php

namespace App\Services;

use App\Models\Todo;
use App\Models\TodoType;
use App\Repositories\TodoRepository;

class TodoService
{
    public function __construct(private TodoRepository $repository)
    {
    }

    public function listForType(TodoType $todoType, int $page, int $perPage, array $filters = [])
    {
        return $this->repository->paginateByType($todoType, $page, $perPage, $filters);
    }

    public function createForType(TodoType $todoType, array $data): Todo
    {
        return $this->repository->createForType($todoType, $data);
    }

    public function show(TodoType $todoType, Todo $todo): Todo
    {
        $this->assertBelongsToType($todoType, $todo);

        return $todo;
    }

    public function update(TodoType $todoType, Todo $todo, array $data): Todo
    {
        $this->assertBelongsToType($todoType, $todo);

        return $this->repository->update($todo, $data);
    }

    public function delete(TodoType $todoType, Todo $todo): void
    {
        $this->assertBelongsToType($todoType, $todo);

        $this->repository->delete($todo);
    }

    private function assertBelongsToType(TodoType $todoType, Todo $todo): void
    {
        if ($todo->todo_type_id !== $todoType->id) {
            abort(404);
        }
    }
}
