<?php

use Slim\App;
use App\Controllers\TaskController;


return function (App $app) {

    $app->get('/tasks', [TaskController::class, 'index']);
    $app->get('/tasks/{id}', [TaskController::class, 'show']);
    $app->post('/tasks', [TaskController::class, 'store']);
    $app->put('/tasks/{id}', [TaskController::class, 'update']);
    $app->delete('/tasks/{id}', [TaskController::class, 'destroy']);
};