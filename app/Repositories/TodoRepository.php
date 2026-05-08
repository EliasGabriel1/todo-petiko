<?php

namespace App\Repositories;

use App\Models\Todo;
use App\Models\TodoType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TodoRepository
{
    public function paginateByType(TodoType $todoType, int $page, int $perPage, array $filters = []): array
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
            'last_page' => $pagination->lastPage(),
            'has_more_pages' => $pagination->hasMorePages(),
        ];
    }

    public function createForType(TodoType $todoType, array $data): Todo
    {
        return $todoType->todos()->create($data);
    }

    public function update(Todo $todo, array $data): Todo
    {
        $todo->update($data);

        return $todo;
    }

    public function delete(Todo $todo): void
    {
        $todo->delete();
    }
}
