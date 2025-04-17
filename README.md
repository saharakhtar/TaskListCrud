# SlimPHP Task List Manager API

This is a basic SlimPHP-based API for managing tasks, using PostgreSQL for the database, and Docker for containerization.

## Requirements

- Docker
- Docker Compose

## Features

- SlimPHP-based REST API
- PostgreSQL integration
- Dockerized setup
- Automatic database migration on container start
- Illuminate ORM
- Swagger documentation (with API Key support)

## Project Structure


## Setup

1. Clone the repository:
    ```bash
    git clone https://github.com/saharakhtar/TaskListCrud.git
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

4. Access the API at `http://localhost:8085`


5. Uses PostgreSQL. Migrations run automatically when the Docker image is built.

### API Documentation

Once the application is running, you can view the Swagger UI for API documentation at:

[http://localhost:8085/swagger-ui/](http://localhost:8085/swagger-ui/)

### API Key

To authorize requests in Swagger UI, add your API Key using the "Authorize" button at the top-right and input:

API_KEY: secert


## API Endpoints

- `GET /tasks`: Get all tasks
- `POST /tasks`: Create a new task
- `PUT /tasks/{id}`: Update a task
- `GET /tasks/{id}`:  Get a single task by ID POST /tasks Create a new task
- `DELETE /tasks/{id}`: Delete a task

## Testing
i didn't implemented php unit testcases

