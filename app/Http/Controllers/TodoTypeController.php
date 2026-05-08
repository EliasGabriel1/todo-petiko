<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;
use App\Http\Controllers\Controller;
use App\Models\TodoType;
use App\Services\TodoTypeService;
use Illuminate\Http\Request;

class TodoTypeController extends Controller
{
    public function __construct(private TodoTypeService $service)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/todo-types",
     *     summary="List todo types",
     *     tags={"Todo Types"},
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
     *     @OA\Response(response=200, description="List of todo types")
     * )
     */
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 15);
        $filters = [
            'search' => $request->query('search'),
        ];

        return response()->json($this->service->list((int) $page, (int) $perPage, $filters));
    }

    /**
     * @OA\Post(
     *     path="/api/todo-types",
     *     summary="Create a todo type",
     *     tags={"Todo Types"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Trabalho")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Todo type created")
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        return response()->json($this->service->create($data), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/todo-types/{todo_type}",
     *     summary="Get todo type with paginated todos",
     *     tags={"Todo Types"},
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
     *     @OA\Response(response=200, description="Todo type detail")
     * )
     */
    public function show(Request $request, TodoType $todoType)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $filters = [
            'search' => $request->query('search'),
            'is_completed' => $request->query('is_completed'),
        ];

        return response()->json($this->service->showWithTodos($todoType, (int) $page, (int) $perPage, $filters));
    }

    /**
     * @OA\Put(
     *     path="/api/todo-types/{todo_type}",
     *     summary="Update a todo type",
     *     tags={"Todo Types"},
     *     @OA\Parameter(
     *         name="todo_type",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Pessoal")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Todo type updated")
     * )
     */
    public function update(Request $request, TodoType $todoType)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        return response()->json($this->service->update($todoType, $data));
    }

    /**
     * @OA\Delete(
     *     path="/api/todo-types/{todo_type}",
     *     summary="Delete a todo type",
     *     tags={"Todo Types"},
     *     @OA\Parameter(
     *         name="todo_type",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Todo type deleted")
     * )
     */
    public function destroy(TodoType $todoType)
    {
        $this->service->delete($todoType);

        return response()->noContent();
    }
}
