<?php
/**
 * Author: Mykola Chomenko
 * Email: mykola.chomenko@dipcom.cz
 * Created: 11.11.2018 12:13
 */

namespace Chomenko\AppWebLoader;

use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;


class AppWebLoaderExtension extends CompilerExtension
{

    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();
        $builder->addDefinition($this->prefix("AppWebLoader"))
            ->setFactory(AppWebLoader::class);
    }

    /**
     * @param Configurator $configurator
     */
    public static function register(Configurator $configurator)
    {
        $configurator->onCompile[] = function ($config, Compiler $compiler){
            $compiler->addExtension('AppWebLoader', new AppWebLoaderExtension());
        };
    }

}