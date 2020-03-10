<?php

namespace Flc\Laravel\Hprose\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class Serve extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'hprose:serve --name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create hprose initial file';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new failed queue jobs table command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->config() as $config) {
            $this->generate(...$config);
        }
    }

    /**
     * 返回配置
     *
     * @return array
     */
    public function config()
    {
        return array(
            array('app/Providers/HproseRouteServiceProvider.php', 'hprose-route-service-provider.stub', 'Hprose service provider'),
            array('routes/hprose.php', 'route.stub', 'Hprose route'),
        );
    }

    /**
     * 生成文件
     *
     * @param string $path
     * @param string $stub
     * @param string $name
     *
     * @return void
     */
    protected function generate($path, $stub, $name)
    {
        $path = base_path($path);
        $stub = $this->files->get(__DIR__.'/stubs/'.$stub);

        if ($this->files->exists($path)) {
            $this->error($name.' already exists!');

            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $stub);

        $this->info($name.' created successfully!');
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param string $path
     *
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }
}
