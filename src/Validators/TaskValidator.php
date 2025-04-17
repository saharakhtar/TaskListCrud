<?php 
namespace App\Validators;

class TaskValidator
{
    public static function validate(array $data): array
    {
        $errors = [];

        // Validate title
        if (!isset($data['title']) || trim($data['title']) === '') {
            $errors['title'] = 'Title is required.';
        } elseif (strlen($data['title']) < 3) {
            $errors['title'] = 'Title must be at least 3 characters long.';
        }

        // Validate completed
        if (isset($data['completed']) && !in_array($data['completed'], [true, false, 1, 0, '1', '0'], true)) {
            $errors['completed'] = 'Completed must be a boolean.';
        }

        return $errors;
    }
}