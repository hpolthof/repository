# Repositories for Laravel
This is a package that has a simple implementation of a repository pattern for use with Eloquent.

## How to use?
Install the package with composer in your project.

```bash
composer require hpolthof/repository
```

### Generator command
Now you can create repositories with a new generator command

```bash
php artisan make:repository {modelName}
```

This will create a new repository in a Repositories directory within the app directory.

### Model Generator
This package will also extend the ```make:model``` generator. When this generator is called
with the parameter ```--all``` or ```-a``` and ```--repository``` or ```-o``` a repository
will also be generated.

## The Repository
The repository is an empty class that extends ```Hpolthof\Laravel\Repository\Repository``` which
implements ```Hpolthof\Laravel\Repository\Contracts\RepositoryInterface```. This interface looks
like:

```php
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
```

### Automatic Model Detection
The repository will try to detect the associated Eloquent model that should be used.
If the model is named `{ModelName}Repository`, the model will automatically be used.

#### Setting the model manually
If the model cannot be detected or if you want to use another name, the `$modelName` 
property should be set. This can be done as in the example below:

```php
use App\Demo;

class DemoRepository extends Repository {
    protected $modelName = Demo::class;
}
```

### Usage in controllers
In a controller you can instantiate a repository using dependency injection. See the example below:

```php
class DemoController extends Controller {
    protected $repository;
    
    public function __construct(DemoRepository $repository)
    {
        $this->repository = $repository;
    }
}
```

## Disclaimer
This package is used for internal development, but published for public use. 
Obviously this software comes *as is*, and there are no warranties or whatsoever.

If you like the package it is always appreciated if you drop a message of gratitude! ;-)

The package was build by: Paul Olthof