<?php

namespace App\Controllers;

use App\Models\Task;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;
use App\Validators\TaskValidator;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     required={"title"},
 *     @OA\Property(property="id", type="integer", description="Task ID"),
 *     @OA\Property(property="title", type="string", description="Task title"),
 *     @OA\Property(property="description", type="string", description="Task description"),
 *     @OA\Property(property="completed", type="boolean", description="Task completion status")
 * )
 */



class TaskController
{
    
    /**
     * @OA\Get(
     *     path="/tasks",
     *     summary="Get all tasks",
     *     security={{"ApiKeyAuth":{}}},
     *     tags={"tasks"},
     *     @OA\Response(
     *         response="200",
     *         description="A list of tasks",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Task"))
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid request"
     *     )
     * )
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();
        $page = isset($params['page']) ? max((int)$params['page'], 1) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $tasks = Task::offset($offset)->limit($limit)->get();
        $response->getBody()->write($tasks->toJson());
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * @OA\Get(
     *     path="/tasks/{id}",
     *     summary="Get task by ID",
     *     tags={"tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Task ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Task details",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Task not found"
     *     )
     * )
     */
    public function show(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $task = Task::find($args['id']);
        if (!$task) {
            $response->getBody()->write(json_encode(['error' => 'Task not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
        $response->getBody()->write($task->toJson());
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * @OA\Post(
     *     path="/tasks",
     *     summary="Create a new task",
     *     tags={"tasks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="New Task"),
     *             @OA\Property(property="description", type="string", example="This is a new task"),
     *             @OA\Property(property="completed", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Task created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation errors"
     *     )
     * )
     */
    public function store(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();
        $errors = TaskValidator::validate($data);

        if (!empty($errors)) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'errors' => $errors,
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(422);
        }

        $task = Task::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'completed' => filter_var($data['completed'] ?? false, FILTER_VALIDATE_BOOLEAN),
        ]);

        $response->getBody()->write(json_encode([
            'status' => 'success',
            'data' => $task,
        ]));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    /**
     * @OA\Put(
     *     path="/tasks/{id}",
     *     summary="Update an existing task",
     *     tags={"tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Task ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Updated Task"),
     *             @OA\Property(property="description", type="string", example="This is an updated task"),
     *             @OA\Property(property="completed", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Task updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Task not found"
     *     )
     * )
     */
    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $args['id'];
        $task = Task::find($id);
    
        if (!$task) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Task not found',
            ]));
    
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    
        $data = $request->getParsedBody();
        $errors = TaskValidator::validate($data);
    
        if (!empty($errors)) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'errors' => $errors,
            ]));
    
            return $response->withHeader('Content-Type', 'application/json')->withStatus(422);
        }
    
        $task->title = $data['title'];
        $task->description = $data['description'] ?? null;
        $task->completed = filter_var($data['completed'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $task->save();
    
        $response->getBody()->write(json_encode([
            'status' => 'success',
            'data' => $task,
        ]));
    
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    /**
     * @OA\Delete(
     *     path="/tasks/{id}",
     *     summary="Delete a task by ID",
     *     tags={"tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Task ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Task deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Task not found"
     *     )
     * )
     */
    public function destroy(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $task = Task::find($args['id']);
        if (!$task) {
            $response->getBody()->write(json_encode(['error' => 'Task not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $task->delete();
        $response->getBody()->write(json_encode(['message' => 'Task deleted']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
