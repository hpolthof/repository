<?php


namespace Hpolthof\Laravel\Repository\Contracts;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function all(array $columns = ['*']): Collection;
    public function list(string $orderByColumn, string $orderBy = 'desc', array $with = [], array $columns = ['*']): Collection;
    public function builder(Closure $builder, array $columns = ['*']): Collection;

    public function create(array $data): ?Model;
    public function update(array $data, $id): bool;
    public function delete($id): bool;

    public function find($id, array $columns = ['*']): ?Model;
    public function findBy(string $field, $value, array $columns = ['*']): ?Model;
}