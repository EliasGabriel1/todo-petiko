<?php

namespace App\Repositories;

use App\Models\TodoType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TodoTypeRepository
{
    public function paginateWithTodoCount(int $page, int $perPage, array $filters = []): array
    {
        $query = TodoType::withCount('todos')
            ->orderBy('name');

        if (! empty($filters['search'])) {
            $query->where('name', 'like', '%'.$filters['search'].'%');
        }

        $pagination = $query->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $pagination->items(),
            'current_page' => $pagination->currentPage(),
            'next_page' => $pagination->hasMorePages() ? $pagination->currentPage() + 1 : null,
            'total' => $pagination->total(),
            'per_page' => $pagination->perPage(),
            'last_page' => $pagination->lastPage(),
            'has_more_pages' => $pagination->hasMorePages(),
        ];
    }

    public function create(array $data): TodoType
    {
        return TodoType::create($data);
    }

    public function update(TodoType $todoType, array $data): TodoType
    {
        $todoType->update($data);

        return $todoType;
    }

    public function delete(TodoType $todoType): void
    {
        $todoType->delete();
    }

    public function paginateTodos(TodoType $todoType, int $page, int $perPage, array $filters = []): array
    {
        $query = $todoType->todos()
            ->orderByDesc('created_at');

        if (! empty($filters['search'])) {
            $query->where('title', 'like', '%'.$filters['search'].'%');
        }

        if (isset($filters['is_completed']) && $filters['is_completed'] !== null) {
            $query->where('is_completed', (bool) $filters['is_completed']);
        }

        $pagination = $query->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $pagination->items(),
            'current_page' => $pagination->currentPage(),
            'next_page' => $pagination->hasMorePages() ? $pagination->currentPage() + 1 : null,
            'total' => $pagination->total(),
            'per_page' => $pagination->perPage(),
        ];
    }
}
