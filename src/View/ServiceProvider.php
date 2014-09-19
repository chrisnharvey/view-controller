<?php

namespace Encore\View;

use Encore\Filesystem\Filesystem;
use Encore\Resource\FileFinder;

class ServiceProvider extends \Encore\Container\ServiceProvider
{
    public function register($binding)
    {
        $finder = new FileFinder(new Filesystem, $this->container['config']['view.paths.style']);
        $finder->addExtension('yml');
        $this->container->bind('view.viewfinder', new FileFinder(new Filesystem, $this->container['config']['view.paths.view']));
        $this->container->bind('view.stylefinder', $finder);
        $this->container->bind('view.styleparser', 'Encore\View\Parser\Style\Yaml');

        $this->container->bind('view', function() {
            return new Manager($this->container['view.viewfinder'], $this->container['view.viewparser'], $this->container['view.stylefinder'], $this->container['view.styleparser']);
        });
    }

    public function provides()
    {
        return ['view', 'view.viewfinder', 'view.stylefinder'];
    }

    public function aliases()
    {
        return [
            'Encore\View\Manager' => 'view'
        ];
    }
}