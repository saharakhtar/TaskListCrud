# SlimPHP Task List Manager API

This is a basic SlimPHP-based API for managing tasks, using PostgreSQL for the database, and Docker for containerization.

## Requirements

- Docker
- Docker Compose

## Setup

1. Clone the repository:
    ```bash
    git clone <your-repository-url>
    cd slimphp-task-manager
    ```

2. Create the `.env` file by copying the example:
    ```bash
    cp .env.example .env
    ```

3. Build the Docker containers:
    ```bash
    docker-compose up --build
    ```

4. Access the API at `http://localhost:8083`

5. Migration will run when DB is created by docker 
   

## API Endpoints

- `GET /tasks`: Get all tasks
- `POST /tasks`: Create a new task
- `PUT /tasks/{id}`: Update a task
- `GET /tasks/{id}`:  Get a single task by ID POST /tasks Create a new task
- `DELETE /tasks/{id}`: Delete a task

## Testing

The API is now ready to be tested.

Use Swagger or Postman for testing.