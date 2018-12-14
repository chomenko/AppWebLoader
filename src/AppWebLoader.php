<?php
/**
 * Author: Mykola Chomenko
 * Email: mykola.chomenko@dipcom.cz
 * Created: 11.11.2018 12:16
 */

namespace Chomenko\AppWebLoader;

use Chomenko\AppWebLoader\Exceptions\AppWebLoaderException;
use Nette\Application\UI\Presenter;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;
use WebLoader\Nette\LoaderFactory;

class AppWebLoader
{

    /**
     * @var Collection[]
     */
    private $collections = array();

    /**
     * @var LoaderFactory
     */
    private $loader_factory;

    /**
     * @var \Mobile_Detect
     */
    private $device;

    /**
     * @var bool
     */
    private $rendered = false;

    /**
     * @var array
     */
    private $style_ext = array('css', 'less', 'sass');


    /**
     * AppWebLoader constructor.
     * @param LoaderFactory $LoaderFactory
     */
    public function __construct(LoaderFactory $LoaderFactory)
    {
        $this->loader_factory = $LoaderFactory;
        $this->device = new \Mobile_Detect;
    }


    /**
     * @param string $name
     * @param Presenter|null $presenter
     * @return CssLoader
     */
    public function createCssLoader(string $name, Presenter $presenter = null):CssLoader
    {
        $this->rendered = true;
        $loader = $this->loader_factory->createCssLoader($name);
        /** @var \WebLoader\FileCollection $collection */
        $collection = $loader->getCompiler()->getFileCollection();

        foreach ($this->getCollections() as $coll){
            foreach ($coll->getStyles() as $file){

                if(!$file->appliesToModule($name)){
                    continue;
                }

                if(!$file->appliesToDevice($this->getDeviceConst())){
                    continue;
                }

				if($file->isFooter()){
					continue;
				}

                $collection->addFile($file->getFile());
            }
        }

        if($path = $this->getActionPath($presenter)){
            foreach ($this->style_ext as $ext) {
                $file = $path . '/' . $presenter->getAction() . '.'. $ext;
                if (file_exists($file)) {
                    $collection->addFile($file);
                    break;
                }
            }
        }

        return $loader;
    }

	/**
	 * @param string $name
	 * @param Presenter|null $presenter
	 * @return CssLoader
	 */
	public function createFooterCssLoader(string $name, Presenter $presenter = null):CssLoader
	{
		$this->rendered = true;
		$loader = $this->loader_factory->createCssLoader($name."Footer");
		/** @var \WebLoader\FileCollection $collection */
		$collection = $loader->getCompiler()->getFileCollection();

		foreach ($this->getCollections() as $coll){
			foreach ($coll->getStyles() as $file){

				if(!$file->isFooter()){
					continue;
				}
				$collection->addFile($file->getFile());
			}
		}

		if($path = $this->getActionPath($presenter)){
			foreach ($this->style_ext as $ext) {
				$file = $path . '/' . $presenter->getAction() . '.'. $ext;
				if (file_exists($file)) {
					$collection->addFile($file);
					break;
				}
			}
		}

		return $loader;
	}



    /**
     * @param string $name
     * @param Presenter|null $presenter
     * @return JavaScriptLoader
     */
    public function createJavaScriptLoader(string $name, Presenter $presenter = null):JavaScriptLoader
    {
        $this->rendered = true;
        $loader = $this->loader_factory->createJavaScriptLoader($name);
        /** @var \WebLoader\FileCollection $collection */
        $collection = $loader->getCompiler()->getFileCollection();

        foreach ($this->getCollections() as $coll){
            foreach ($coll->getScripts() as $file){

                if(!$file->appliesToModule($name)){
                    continue;
                }

                if(!$file->appliesToDevice($this->getDeviceConst())){
                    continue;
                }
                $collection->addFile($file->getFile());
            }
        }

        if($path = $this->getActionPath($presenter)){
            $file = $path . '/' .$presenter->getAction().'.js';
            if(file_exists($file)){
                $collection->addFile($file);
            }
        }

        return $loader;
    }


    /**
     * @return \Mobile_Detect
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * @param string $name
     * @return Collection
     * @throws AppWebLoaderException
     */
    public function createCollection(string $name): Collection
    {
        if(array_key_exists($name, $this->collections)){
            throw AppWebLoaderException::collectionExists($name);
        }
        return $this->collections[$name] = new Collection($this, $name);
    }

    /**
     * @return Collection[]
     */
    public function getCollections(): array
    {
        return $this->collections;
    }

    /**
     * @return bool
     */
    public function isRendered(): bool
    {
        return $this->rendered;
    }

    /**
     * @return string
     */
    protected function getDeviceConst()
    {
        return $this->device->isMobile() ? File::ONLY_MOBILE : File::ONLY_DESKTOP;
    }

    /**
     * @param Presenter|null $presenter
     * @return bool|string
     */
    protected function getActionPath(Presenter $presenter = null)
    {
        if($presenter){
            return dirname($presenter->getTemplate()->getFile());
        }
        return false;
    }

}
