<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    /**
     * Get all records
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Get paginated records
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    /**
     * Find record by ID
     */
    public function find(int $id, array $columns = ['*']): ?Model;

    /**
     * Find record by ID or fail
     */
    public function findOrFail(int $id, array $columns = ['*']): Model;

    /**
     * Find record by attribute
     */
    public function findBy(string $attribute, $value, array $columns = ['*']): ?Model;

    /**
     * Find records by attribute
     */
    public function findAllBy(string $attribute, $value, array $columns = ['*']): Collection;

    /**
     * Create new record
     */
    public function create(array $attributes): Model;

    /**
     * Update record
     */
    public function update(int $id, array $attributes): bool;

    /**
     * Update or create record
     */
    public function updateOrCreate(array $conditions, array $attributes): Model;

    /**
     * Delete record
     */
    public function delete(int $id): bool;

    /**
     * Get records with relationships
     */
    public function with(array $relations): self;

    /**
     * Apply where conditions
     */
    public function where(string $column, string $operator, $value = null): self;

    /**
     * Apply where in conditions
     */
    public function whereIn(string $column, array $values): self;

    /**
     * Apply ordering
     */
    public function orderBy(string $column, string $direction = 'asc'): self;

    /**
     * Apply search conditions
     */
    public function search(string $query, array $columns = []): self;

    /**
     * Count records
     */
    public function count(): int;

    /**
     * Check if record exists
     */
    public function exists(int $id): bool;
}
