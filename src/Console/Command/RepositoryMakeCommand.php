<?php


namespace Hpolthof\Laravel\Repository\Console\Command;


use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class RepositoryMakeCommand extends GeneratorCommand
{
    protected $name = 'make:repository';
    protected $description = 'Create a new repository class';
    protected $type = 'Repository';

    protected function getStub()
    {
        return __DIR__ . '/stubs/repository.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\Repositories';
    }

    protected function getNameInput()
    {
        $name = parent::getNameInput();

        if (!Str::endsWith($name, $this->type)) {
            $name .= $this->type;
        }

        return Str::studly($name);
    }


}