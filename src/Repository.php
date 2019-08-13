<?php


namespace Hpolthof\Laravel\Repository;


use Closure;
use Hpolthof\Laravel\Repository\Contracts\RepositoryInterface;
use Hpolthof\Laravel\Repository\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;

abstract class Repository implements RepositoryInterface
{
    private $application;
    private $instantiatedModel;

    protected $modelName = null;

    public function __construct(Application $application)
    {
        $this->application = $application;
        $this->instantiatedModel = $this->instantiateModel();
    }

    protected function model(): string
    {
        // Use the modelName
        if ($this->modelName) {
            return $this->modelName;
        }

        // Or try to resolve the model based on the name of the repository
        $baseClass = class_basename(static::class);
        if (Str::endsWith($baseClass, 'Repository')) {
            $modelName = app()->getNamespace().Str::before($baseClass, 'Repository');

            if (class_exists($modelName)) {
                return $modelName;
            }
        }

        // Or Fail...
        throw new RepositoryException("No model could be found. You should set the fully qualified name of your Eloquent model with the 'modelName' property of your repository.");
    }

    final private function instantiateModel(): Model
    {
        $model = $this->application->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException("The repository {$this->model()} has to be an instance of ".Model::class);
        }

        return $model;
    }

    public function __call($name, $arguments)
    {
        if (Str::startsWith($name, 'findBy') && strlen($name) > strlen('findBy')) {
            $fieldName = Str::snake(Str::after($name, 'findBy'));

            return $this->findBy(...array_merge([$fieldName], $arguments));
        }
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->query()
            ->get($columns);
    }

    public function list(string $orderByColumn, string $orderBy = 'asc', array $with = [], array $columns = ['*']): Collection
    {
        return $this->query()
            ->with($with)
            ->orderBy($orderByColumn, $orderBy)
            ->get($columns);
    }

    public function builder(Closure $builder, array $column = ['*']): Collection
    {
        $query = $this->query();
        $builder($query);

        return $query->get();
    }

    public function create(array $data): ?Model
    {
        return $this->query()->create($data);
    }

    public function update(array $data, $id): bool
    {
        $item = $this->find($id);
        if (!$item)
            return false;

        return $item->update($data);
    }

    public function delete($id): bool
    {
        $item = $this->find($id);
        if (!$item)
            return false;

        return $item->delete();
    }

    public function find($id, array $columns = ['*']): ?Model
    {
        return $this->query()
            ->find($id, $columns);
    }

    public function findBy(string $field, $value, array $columns = ['*']): ?Model
    {
        return $this->query()
            ->where($field, $value)
            ->first($columns);
    }

    protected function query(): Builder
    {
        return $this->instantiatedModel->query();
    }

}