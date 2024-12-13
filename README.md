# BudgetManager

## Overview

This project is a Symfony-based web application designed to manage users and envelopes using Domain-Driven Design (DDD) and Command Query Responsibility Segregation (CQRS). It includes features for creating, updating, and querying users and envelopes. The application uses SQL for write operations and Elasticsearch as a query database for read operations.

## Features

- **User Management**: Create and manage user accounts.
- **Envelope Management**: Create and manage envelopes, including nested envelopes.
- **Validation**: Ensure data integrity with Symfony's validation constraints.
- **Logging**: Comprehensive logging for error tracking and debugging.
- **Security**: Role-based access control for secure operations.
- **CQRS**: Separate command and query responsibilities for better scalability and maintainability.
- **DDD**: Domain-driven design principles for a robust and scalable architecture.

## Technical Stack

- **Language**: PHP
- **Framework**: Symfony
- **Package Manager**: Composer
- **Containerization**: Docker
- **Database**: Doctrine ORM (SQL for write operations)
- **Query Database**: Elasticsearch (for read operations)
- **Testing**: PHPUnit
- **Code Quality**: PHPStan, Rector, PHP-CS-Fixer

## Getting Started

### Prerequisites

- Docker
- Docker Compose

### Installation

1. **Clone the repository**:
    ```sh
    git clone <repository-url>
    cd <repository-directory>
    ```

2. **Start Docker containers**:
    ```sh
    make up
    ```

3. **Install dependencies**:
    ```sh
    make composer-install
    ```

4. **Create the database**:
    ```sh
    make database-create
    ```

5. **Apply database migrations**:
    ```sh
    make migration-apply
    ```

6. **Start the Symfony server**:
    ```sh
    make start-server
    ```

### Additional Commands

- **Stop Docker containers**:
    ```sh
    make down
    ```

- **Clear Symfony cache**:
    ```sh
    make cache-clear
    ```

- **Run tests**:
    ```sh
    make phpunit
    ```

- **Run code quality checks**:
    ```sh
    make phpstan
    make rector
    make cs-fixer
    ```