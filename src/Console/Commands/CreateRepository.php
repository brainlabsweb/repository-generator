<?php

namespace Brainlabs\Generator\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;

class CreateRepository extends Command
{

    protected $stub_path;
    protected $dir_path;
    protected $publish_path;
    protected $name;
    protected $contract_name;
    protected $repo_name;
    protected $controller_namespace = 'App\\Http\\Controllers';

    protected $keys = [
        '/{{namespace}}/', '/{{contract}}/', '/{{repositoryName}}/', '/{{variableName}}/', '/{{contractUse}}/', '/{{className}}/',
        '/{{controllerNamespace}}/', '/{{modelImport}}/','/{{modelVariable}}/', '/{{modelName}}/',
    ];


    protected $signature = 'make:repository 
    {name : The name of the repository} 
    {c? : Creates a Controller} 
    {m? : Creates a Model}';

    /**
     * CreateRepository constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->stub_path = __DIR__ . '/../../stubs';
        $this->dir_path = Config::get('repository.dir_name');
    }

    protected $description = 'Create a repository';

    /**
     * creates the repository, controller and model on request
     */
    public function handle()
    {
        $this->name = Str::studly($this->argument('name'));
        $this->repo_name = $this->name . 'Repository';
        $this->contract_name = $this->name . 'Contract';

        $this->createRepo()
            ->createController();

        $this->info("Repository `{$this->repo_name}` created");
    }

    /**
     * create the repository
     */
    protected function createRepo()
    {
        $this->createFileDirectory()
            ->createConcreteClass()
            ->createContract();

        return $this;
    }

    /**
     * create contract
     * @return $this
     */
    private function createContract()
    {
        $repository_stub_contract = file_get_contents($this->stub_path . '/repository_contract.php');
        file_put_contents($this->getFileName($this->publish_path, $this->contract_name), $this->replaceKeyMaps($repository_stub_contract));
        $this->comment($this->contract_name . ' created successfully');

        return $this;
    }

    /**
     * create concrete class
     * @return $this
     */
    private function createConcreteClass()
    {
        $repository_stub = file_get_contents($this->stub_path . '/repository.php');

        if ($this->argument('m')) {
            $repository_stub = file_get_contents($this->stub_path . '/repository_with_model.php');
            $this->createModel();
        }
        file_put_contents($this->getFileName($this->publish_path, $this->repo_name), $this->replaceKeyMaps($repository_stub));
        $this->comment($this->repo_name . ' created successfully');

        return $this;
    }

    /**
     * create controller
     * @return $this
     */
    private function createController()
    {
        if ($this->argument('c')) {
            $controller = $this->getClassName();

            $controller_stub = file_get_contents($this->stub_path . '/controller.php');

            file_put_contents($this->getFileName($this->controller_namespace, $controller), $this->replaceKeyMaps($controller_stub));
            $this->comment($controller . ' created successfully');
        }

        return $this;
    }

    /**
     * replaces stub keys with their respective values
     * @param  string  $stub
     * @return string
     */
    private function replaceKeyMaps(string $stub)
    : string
    {
        foreach ($this->keys as $key) {
            $mapKey = $this->keyMap($key);
            if (!is_null($mapKey)) {
                $stub = preg_replace($key, $mapKey, $stub);
            }
        }
        return $stub;
    }


    /**
     * create model
     * @return $this
     */
    private function createModel()
    {
        if ($this->argument('m')) {

            Artisan::call('make:model ' . $this->name);
        }

        return $this;
    }

    /**
     * create directory
     * @return CreateRepository
     */
    protected function createFileDirectory()
    {
        $this->publish_path = app_path("{$this->dir_path}/{$this->name}");
        if (!is_dir($this->publish_path)) {
            mkdir($this->publish_path, 0755, true);
        }
        return $this;
    }

    /**
     * get the name space of the container
     * @return string
     */
    protected function getNamespace()
    {
        $namespace = Container::getInstance()->getNamespace();
        return rtrim($namespace . '\\' . $this->dir_path . '\\' . $this->name, '\\');
    }

    /**
     * map static keys to their values and return value based on the key given
     * @param $key
     * @return string
     */
    private function keyMap($key)
    : string
    {
        return [
                '/{{namespace}}/'           => $this->getNamespace(),
                '/{{contract}}/'            => Str::studly($this->contract_name),
                '/{{repositoryName}}/'      => Str::singular(Str::studly($this->repo_name)),
                '/{{variableName}}/'        => Str::singular(Str::camel($this->contract_name)),
                '/{{contractUse}}/'         => $this->getNamespace() . '\\' . Str::studly($this->contract_name),
                '/{{className}}/'           => $this->getClassName(),
                '/{{controllerNamespace}}/' => $this->controller_namespace,
                '/{{modelImport}}/'         => "App\\". $this->name,
                '/{{modelVariable}}/'       => Str::singular(Str::camel($this->name)),
                '/{{modelName}}/'           => $this->name,
            ][$key] ?? null;
    }

    /**
     * used when controller being generated
     * @return string
     */
    private function getClassName()
    : string
    {
        if (!Str::contains($this->name, 'Controller')) {
            return $this->name . 'Controller';
        }
        return $this->name;
    }

    /**
     * get the file name in which the content goes
     * @param  string  $namespace
     * @param  string  $file_name
     * @return string
     */
    private function getFileName(string $namespace, string $file_name)
    : string
    {
        return $namespace . '\\' . $file_name . '.php';
    }
}