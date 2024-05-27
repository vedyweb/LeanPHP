<?php

namespace leanphp\core;

class Application
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function run()
    {
        // Uygulama çalıştırma kodları
        // Örneğin, route dispatching vb.
        // Update vs gibi şeyler de olabilir.
    }
}
