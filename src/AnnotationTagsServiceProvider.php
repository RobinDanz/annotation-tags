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
        $this->loadViewsFrom(__DIR__.'/resources/views', 'annotation-tags');

        $modules->register('annotation-tags', [
            'viewMixins' => [
                'annotationsAnnotationsTab',
            ]
        ]);

        $this->publishes([
            __DIR__.'/public/assets' => public_path('vendor/annotation-tags'),
        ], 'public');
    }

    /**
    * Register the service provider.
    *
    * @return  void
    */
    public function register()
    {
        //
    }
}
