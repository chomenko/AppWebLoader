<?php
/**
 * Author: Mykola Chomenko
 * Email: mykola.chomenko@dipcom.cz
 * Created: 11.11.2018 12:35
 */

namespace Chomenko\AppWebLoader\Exceptions;


class AppWebLoaderException extends \Exception
{
    /**
     * @param string $name
     * @return AppWebLoaderException
     */
    public static function collectionExists(string $name)
    {
        return new self("The collection \"{$name}\" exists");
    }

    /**
     * @param string $prefix
     * @param $class
     * @return AppWebLoaderException
     */
    public static function notAllowConstType(string $prefix, $class)
    {
        return new self("Unauthorized type of constant. Please use any of these options \"{$class}::{$prefix}[type]\"");
    }

    /**
     * @param string $collection_name
     * @return AppWebLoaderException
     */
    public static function alreadyRenderedCollection(string $collection_name)
    {
        return new self("Can not create collections \"$collection_name\"  after rendering.");
    }

    /**
     * @param string $file
     * @return AppWebLoaderException
     */
    public static function alreadyRenderedFile(string $file)
    {
        return new self("Can not add file \"$file\" after rendering.");
    }

    /**
     * @return AppWebLoaderException
     */
    public static function canNotChange()
    {
        return new self("Can not change setting after rendering.");
    }

    /**
     * @param $file
     * @return AppWebLoaderException
     */
    public static function fileNotFound($file)
    {
        return new self("file \"{$file}\" not found.");
    }

}