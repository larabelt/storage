<?php

namespace Belt\Storage;

use Belt;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class BeltStorageServiceProvider extends ServiceProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Belt\Storage\File::class => Belt\Storage\Policies\FilePolicy::class,
    ];

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/../routes/admin.php';
        include __DIR__ . '/../routes/api.php';
        include __DIR__ . '/../routes/web.php';
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(GateContract $gate, Router $router)
    {

        // set view paths
        $this->loadViewsFrom(resource_path('belt/storage/views'), 'belt-storage');

        // set backup view paths
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'belt-storage');

        // policies
        $this->registerPolicies($gate);

        // morphMap
        Relation::morphMap([
            'files' => Belt\Storage\File::class,
        ]);

        // commands
        $this->commands(Belt\Storage\Commands\FakerCommand::class);
        $this->commands(Belt\Storage\Commands\PublishCommand::class);
        $this->commands(Belt\Storage\Commands\ResizeCommand::class);
    }

    /**
     * Register the application's policies.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate $gate
     * @return void
     */
    public function registerPolicies(GateContract $gate)
    {
        foreach ($this->policies as $key => $value) {
            $gate->policy($key, $value);
        }
    }

}