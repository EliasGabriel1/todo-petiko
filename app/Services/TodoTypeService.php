<?php

namespace App\Services;

use App\Models\TodoType;
use App\Repositories\TodoTypeRepository;

class TodoTypeService
{
    public function __construct(private TodoTypeRepository $repository)
    {
    }

    public function list(int $page, int $perPage, array $filters = [])
    {
        return $this->repository->paginateWithTodoCount($page, $perPage, $filters);
    }

    public function create(array $data): TodoType
    {
        return $this->repository->create($data);
    }

    public function showWithTodos(TodoType $todoType, int $page, int $perPage, array $filters = []): array
    {
        return [
            'type' => $todoType,
            'todos' => $this->repository->paginateTodos($todoType, $page, $perPage, $filters),
        ];
    }

    public function update(TodoType $todoType, array $data): TodoType
    {
        return $this->repository->update($todoType, $data);
    }

    public function delete(TodoType $todoType): void
    {
        $this->repository->delete($todoType);
    }
}
