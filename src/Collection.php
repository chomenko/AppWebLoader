<?php
/**
 * Author: Mykola Chomenko
 * Email: mykola.chomenko@dipcom.cz
 * Created: 11.11.2018 12:32
 */

namespace Chomenko\AppWebLoader;


use Chomenko\AppWebLoader\Exceptions\AppWebLoaderException;

class Collection
{

    /**
     * @var AppWebLoader
     */
    private $parent;

    /**
     * @var string
     */
    private $name;

    /**
     * @var File[]
     */
    private $scripts = array();

    /**
     * @var File[]
     */
    private $styles = array();


    /**
     * Collection constructor.
     * @param AppWebLoader $parent
     * @param string $name
     */
    public function __construct(AppWebLoader $parent, string $name)
    {
        $this->parent = $parent;
        $this->name = $name;
    }

    /**
     * @return AppWebLoader
     */
    public function getParent():AppWebLoader
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

	/**
	 * @param string $file_name
	 * @return File
	 * @throws AppWebLoaderException
	 * @throws \ReflectionException
	 */
    public function addScript(string $file_name):File
    {
        if(!file_exists($file_name)){
            throw AppWebLoaderException::fileNotFound($file_name);
        }

		$footer = false;
        if($this->parent->isRendered()){
			$footer = true;
		}

        return $this->scripts[] = new File($this, $file_name, File::TYPE_STYLE, $footer);
    }

    /**
     * @param string $file_name
     * @return File
     * @throws Exceptions\AppWebLoaderException
     * @throws \ReflectionException
     */
    public function addStyles(string $file_name):File
    {

        if(!file_exists($file_name)){
            throw AppWebLoaderException::fileNotFound($file_name);
        }

		$footer = false;
		if($this->parent->isRendered()){
			$footer = true;
		}
        return $this->styles[] = new File($this, $file_name, File::TYPE_STYLE, $footer);
    }

    /**
     * @return File[]
     */
    public function getScripts(): array
    {
        return $this->scripts;
    }

    /**
     * @return File[]
     */
    public function getStyles(): array
    {
        return $this->styles;
    }


}