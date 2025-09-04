<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;
    protected Builder $query;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->resetQuery();
    }

    /**
     * Reset the query builder
     */
    protected function resetQuery(): void
    {
        $this->query = $this->model->newQuery();
    }

    /**
     * Get all records
     */
    public function all(array $columns = ['*']): Collection
    {
        $result = $this->query->get($columns);
        $this->resetQuery();
        return $result;
    }

    /**
     * Get paginated records
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        $result = $this->query->paginate($perPage, $columns);
        $this->resetQuery();
        return $result;
    }

    /**
     * Find record by ID
     */
    public function find(int $id, array $columns = ['*']): ?Model
    {
        return $this->model->find($id, $columns);
    }

    /**
     * Find record by ID or fail
     */
    public function findOrFail(int $id, array $columns = ['*']): Model
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * Find record by attribute
     */
    public function findBy(string $attribute, $value, array $columns = ['*']): ?Model
    {
        return $this->model->where($attribute, $value)->first($columns);
    }

    /**
     * Find records by attribute
     */
    public function findAllBy(string $attribute, $value, array $columns = ['*']): Collection
    {
        return $this->model->where($attribute, $value)->get($columns);
    }

    /**
     * Create new record
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * Update record
     */
    public function update(int $id, array $attributes): bool
    {
        return $this->model->where('id', $id)->update($attributes);
    }

    /**
     * Update or create record
     */
    public function updateOrCreate(array $conditions, array $attributes): Model
    {
        return $this->model->updateOrCreate($conditions, $attributes);
    }

    /**
     * Delete record
     */
    public function delete(int $id): bool
    {
        return $this->model->destroy($id);
    }

    /**
     * Get records with relationships
     */
    public function with(array $relations): self
    {
        $this->query->with($relations);
        return $this;
    }

    /**
     * Apply where conditions
     */
    public function where(string $column, string $operator, $value = null): self
    {
        if ($value === null) {
            $this->query->where($column, $operator);
        } else {
            $this->query->where($column, $operator, $value);
        }
        return $this;
    }

    /**
     * Apply where in conditions
     */
    public function whereIn(string $column, array $values): self
    {
        $this->query->whereIn($column, $values);
        return $this;
    }

    /**
     * Apply ordering
     */
    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->query->orderBy($column, $direction);
        return $this;
    }

    /**
     * Apply search conditions
     */
    public function search(string $query, array $columns = []): self
    {
        if (empty($columns)) {
            $columns = ['name']; // Default search column
        }

        $this->query->where(function ($q) use ($query, $columns) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', "%{$query}%");
            }
        });

        return $this;
    }

    /**
     * Count records
     */
    public function count(): int
    {
        $result = $this->query->count();
        $this->resetQuery();
        return $result;
    }

    /**
     * Check if record exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }
}
