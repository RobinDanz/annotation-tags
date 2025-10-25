<?php

namespace Biigle\Modules\AnnotationTags;

use Biigle\Services\Modules;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class AnnotationTagsServiceProvider extends ServiceProvider
{

   /**
   * Bootstrap the application events.
   *
   * @param Modules $modules
   * @param  Router  $router
   * @return  void
   */
    public function boot(Modules $modules, Router $router)
    {   
        $this->loadMigrationsFrom(__DIR__.'/Database/migrations');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'annotation-tags');

        $router->group([
            'namespace' => 'Biigle\Modules\AnnotationTags\Http\Controllers',
            'middleware' => 'web',
        ], function ($router) {
            require __DIR__.'/Http/routes.php';
        });

        $modules->register('annotation-tags', [
            'viewMixins' => [
                'annotationsAnnotationsTab',
                'dashboardButtons',
                'projectsShowTabs',
            ]
        ]);

        $this->publishes([
            __DIR__.'/public' => public_path('vendor/annotation-tags'),
        ], 'public');
    }

    /**
    * Register the service provider.
    *
    * @return  void
    */
    public function register()
    {
        // $this->mergeConfigFrom(__DIR__.'/config/annotation_tags.php', 'reports');
        $scripts = config('reports.scripts', []);
        $scripts['to_coco_with_tags'] = __DIR__.'/resources/scripts/to_coco_with_tags.py';
        config(['reports.scripts' => $scripts]);

        $this->app->singleton('command.annotation-tags.publish', function ($app) {
            return new \Biigle\Modules\AnnotationTags\Console\Commands\Publish();
        });

        $this->commands('command.annotation-tags.publish');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.annotation-tags.publish',
        ];
    }
}
