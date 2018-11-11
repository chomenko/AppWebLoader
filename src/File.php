<?php
/**
 * Author: Mykola Chomenko
 * Email: mykola.chomenko@dipcom.cz
 * Created: 11.11.2018 13:23
 */

namespace Chomenko\AppWebLoader;

use Chomenko\AppWebLoader\Exceptions\AppWebLoaderException;

class File
{

    const

        /**
         * File type
         */
        TYPE_STYLE = "#style",
        TYPE_SCRIPT = "#script",

        /**
         * Applies only to devices
         */
        ONLY_ALL = "#all",
        ONLY_MOBILE = "#mobile",
        ONLY_DESKTOP = "#desktop";


    /**
     * @var string
     */
    protected $file;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var Collection
     */
    protected $parent;

    /**
     * @var null|string
     */
    protected $device = self::ONLY_ALL;

    /**
     * @var string|null
     */
    protected $module;

    /**
     * File constructor.
     * @param Collection $parent
     * @param string $file
     * @param string $type
     * @throws AppWebLoaderException
     * @throws \ReflectionException
     */
    public function __construct(Collection $parent, string $file, string $type)
    {
        if(!$this->verifyConst("TYPE_", $type)){
            throw AppWebLoaderException::notAllowConstType("TYPE_", self::class);
        }

        $this->file = $file;
        $this->type = $type;
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }


    /**
     * @param string|null $module_name
     * @throws AppWebLoaderException
     */
    public function setForModule(string $module_name = null)
    {
        if($this->parent->getParent()->isRendered()){
            throw AppWebLoaderException::canNotChange();
        }
        $this->module = $module_name;
    }

    /**
     * @param string $module
     * @return bool
     */
    public function appliesToModule(string $module)
    {
        if($this->module === null || $this->module === $module){
            return true;
        }
        return false;
    }

    /**
     * @param string $device_type
     * @return bool
     */
    public function appliesToDevice(string $device_type)
    {
        if($this->device === self::ONLY_ALL){
            return true;
        }
        return $this->device === $device_type;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $const
     * @throws AppWebLoaderException
     * @throws \ReflectionException
     */
    public function setOnlyFor(string $const)
    {
        if($this->parent->getParent()->isRendered()){
            throw AppWebLoaderException::canNotChange();
        }

        if(!$this->verifyConst("ONLY_", $const)){
            throw AppWebLoaderException::notAllowConstType("ONLY_", self::class);
        }
        $this->device = $const;
    }


    /**
     * @return null|string
     */
    public function getForDevice()
    {
        return $this->device;
    }

    /**
     * @return Collection
     */
    public function getParent():Collection
    {
        return $this->parent;
    }

    /**
     * @param string $prefix
     * @param string $value
     * @return bool
     * @throws \ReflectionException
     */
    protected function verifyConst(string $prefix, string $value):bool
    {
        $reflector = new \ReflectionClass($this);
        $reflector->getConstants();
        foreach($reflector->getConstants() as $name => $val)
        {
            if(substr($name, 0, strlen($prefix)) == $prefix && $value === $val){
                return true;
            }
        }
        return false;
    }

}