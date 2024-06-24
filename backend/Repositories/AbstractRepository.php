<?php

namespace App\Repositories;
use App\Helpers\ArrayHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

abstract class AbstractRepository
{
    protected Model $model;

    /**
     * @param string $resourceClass fully qualified Model class name
     */
    public function __construct(string $resourceClass)
    {
        $this->model = app($resourceClass);
    }

    public function getModelName(): string
    {
        return class_basename($this->model);
    }

    // TODO: refactor function so it doesn't have more than 2 arguments
    /**
     * @param int|null $pagination
     * @param int $page
     * @param array|null $filter
     * @param array|null $sort
     * @return mixed
     */
    public function getAll(?int $pagination=null, int $page=1, ?array $filter=null, ?array $sort=null): mixed
    {
        $query = $this->model->newQuery();
        if($filter) {
            $this->applyFilter($query, $filter);
        }

        if($sort) {
            $this->applySort($query, $sort);
        }

        if($pagination) {
            return $query->orderBy('id', 'desc')->paginate($pagination, ['*'], 'page', $page);
        }

        return $query->get();
    }

    /**
     * @param string $key
     * @param array $data
     * @return Builder[]|Collection
     */
    public function getIn(string $key, array $data): Collection|array
    {
        $query = $this->model->newQuery();
        return $query->whereIn($key, $data)->get();
    }

    /**
     * @param string $key
     * @param string $start
     * @param string $end
     * @return Builder[]|Collection
     */
    public function getBetween(string $key, string $start, string $end): Collection|array
    {
        $query = $this->model->newQuery();
        return $query->whereBetween($key, [$start, $end])->get();
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function getLimited(int $limit): mixed
    {
        return $this->model->orderBy('id', 'desc')->limit($limit)->get();
    }

    /**
     * Find a model by id
     * @param int $id
     * @return Model|null
     */
    public function findById(int $id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param array $attributes
     * @return Model|null
     */
    public function findByParams(array $attributes): ?Model
    {
        return $this->model->where(function ($query) use ($attributes) {
            foreach ($attributes as $key=>$attribute) {
                $query->when($attribute, function ($query) use ($key, $attribute) {
                    return $query->where($key, $attribute);
                });
            }
        })->first() ?? null;
    }

    /**
     * Create new record with specified values
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * Update model with respective values
     * @param int $id
     * @param array $attributes
     * @return bool
     */
    public function update(int $id, array $attributes): bool
    {
        return $this->findById($id)->update($attributes);
    }

    /**
     * Delete model matching for current id
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->findById($id)->delete();
    }

    /**
     * @param Builder $query
     * @param array $filter
     */
    protected function applyFilter(Builder $query, array $filter): void
    {
        foreach ($filter as $col => $val) {
            if(str_contains($col, ':')) {
                $attr = explode(":", $col);
                $query->whereHas($attr[0], function ($q) use ($attr, $val) {
                    $q->where($attr[1], $val);
                });
            } elseif(str_contains($col, ',')) {
                $arr = ArrayHelpers::getArrayFromString($col);
                if(in_array('like', $arr)){
                    $val = '%'.$val.'%';
                }

                if(in_array('or', $arr)) {
                    $query->orWhere($arr[0], $arr[1], $val);
                } else {
                    $query->where($arr[0], $arr[1], $val);
                }

            } else {
                $query->where($col, $val);
            }
        }
    }

    /**
     * @param Builder $query
     * @param array $sort
     * @return void
     */
    protected function applySort(Builder $query, array $sort): void
    {
        foreach ($sort as $col => $direction) {
            if(str_contains($col, ':')) {
                $attr = explode(":", $col);
                $query->whereHas($attr[0], function ($q) use ($attr, $direction) {
                    $q->orderBy($attr[1], $direction);
                });
            } else {
                $query->orderBy($col, $direction);
            }
        }
    }
}
