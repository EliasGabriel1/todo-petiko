<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;
use App\Models\Todo;
use App\Models\TodoType;
use App\Services\TodoService;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function __construct(private TodoService $service)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/todo-types/{todo_type}/todos",
     *     summary="List todos for a todo type",
     *     tags={"Todos"},
     *     @OA\Parameter(
     *         name="todo_type",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="is_completed",
     *         in="query",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response=200, description="List of todos")
     * )
     */
    public function index(Request $request, TodoType $todoType)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $filters = [
            'search' => $request->query('search'),
            'is_completed' => $request->query('is_completed'),
        ];

        return response()->json($this->service->listForType($todoType, (int) $page, (int) $perPage, $filters));
    }

    /**
     * @OA\Post(
     *     path="/api/todo-types/{todo_type}/todos",
     *     summary="Create a todo in a todo type",
     *     tags={"Todos"},
     *     @OA\Parameter(
     *         name="todo_type",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Comprar leite"),
     *             @OA\Property(property="description", type="string", example="Comprar leite no mercado"),
     *             @OA\Property(property="is_completed", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Todo created")
     * )
     */
    public function store(Request $request, TodoType $todoType)
    {

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_completed' => ['nullable', 'boolean'],
            'due_date' => ['nullable', 'date'],
        ]);

        $token = $request->bearerToken();
        if (! $token) {
            return response()->json(['message' => 'Token não fornecido'], 401);
        }

        $authAdapter = app(\App\Adapters\AuthServiceAdapterInterface::class);
        $user = $authAdapter->me($token);

        if (! $user) {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        if (! isset($user['is_admin']) || $user['is_admin'] !== 1) {
            return response()->json(['message' => 'Apenas administradores podem criar tarefas'], 403);
        }

        $todo = $this->service->createForType($todoType, array_merge(['is_completed' => false], $data));

        return response()->json($todo, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/todo-types/{todo_type}/todos/{todo}",
     *     summary="Get a specific todo",
     *     tags={"Todos"},
     *     @OA\Parameter(
     *         name="todo_type",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="todo",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Todo returned")
     * )
     */
    public function show(TodoType $todoType, Todo $todo)
    {
        return response()->json($this->service->show($todoType, $todo));
    }

    /**
     * @OA\Put(
     *     path="/api/todo-types/{todo_type}/todos/{todo}",
     *     summary="Update a specific todo",
     *     tags={"Todos"},
     *     @OA\Parameter(
     *         name="todo_type",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="todo",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Comprar leite"),
     *             @OA\Property(property="description", type="string", example="Ir ao mercado"),
     *             @OA\Property(property="is_completed", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Todo updated")
     * )
     */
    public function update(Request $request, TodoType $todoType, Todo $todo)
    {
        $data = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_completed' => ['sometimes', 'boolean'],
        ]);

        return response()->json($this->service->update($todoType, $todo, $data));
    }

    /**
     * @OA\Delete(
     *     path="/api/todo-types/{todo_type}/todos/{todo}",
     *     summary="Delete a specific todo",
     *     tags={"Todos"},
     *     @OA\Parameter(
     *         name="todo_type",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="todo",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Todo deleted")
     * )
     */
    public function destroy(TodoType $todoType, Todo $todo)
    {
        $this->service->delete($todoType, $todo);

        return response()->noContent();
    }
}
