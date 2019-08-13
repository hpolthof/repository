<?php


namespace Hpolthof\Laravel\Repository\Console\Command;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ModelMakeCommand extends \Illuminate\Foundation\Console\ModelMakeCommand
{
    public function handle()
    {
        if (parent::handle() === false)
            return false;

        if ($this->option('all') || $this->option('repository')) {
            $this->createRepository();
        }
    }

    protected function createRepository()
    {
        $repository = Str::studly(class_basename($this->argument('name')));

        $this->call('make:repository', [
            'name' => $repository,
        ]);
    }

    protected function getOptions()
    {
        return array_merge(parent::getOptions(), [
            ['repository', 'o', InputOption::VALUE_NONE, 'Create a new repository for the model'],
        ]);
    }

}