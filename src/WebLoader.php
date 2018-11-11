<?php
/**
 * Author: Mykola Chomenko
 * Email: mykola.chomenko@dipcom.cz
 * Created: 11.11.2018 15:38
 */

namespace Chomenko\AppWebLoader;

use Nette\Application\Helpers;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;

/** @mixin \Nette\Application\UI\Presenter*/
trait WebLoader
{

    /**
     * @var AppWebLoader @inject
     */
    public $appWebLoader;

    /**
     * @return null
     */
    public function getModuleName()
    {
        $module = Helpers::splitName($this->getName())[0];
        return empty($module) ? null : $module;
    }

    /**
     * @return CssLoader
     */
    protected function createComponentCss():CssLoader
    {
        $module = Helpers::splitName($this->getName())[0];
        empty($module) ? 'default' : $module;
        $CssLoader =  $this->appWebLoader->createCssLoader($module, $this);
        return $CssLoader;
    }

    /**
     * @return JavaScriptLoader
     */
    protected function createComponentJs():JavaScriptLoader
    {
        $module = Helpers::splitName($this->getName())[0];
        empty($module) ? 'default' : $module;
        $ScriptLoader = $this->appWebLoader->createJavaScriptLoader($module, $this);
        return $ScriptLoader;
    }

}