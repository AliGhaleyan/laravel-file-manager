<?php
/**
 * Created by SERJIK
 */

namespace AliGhale\FileManager;

use AliGhale\FileManager\BaseType;

class File
{
    /**
     * select type
     *
     * @param null $type
     * @return \AliGhale\FileManager\BaseType
     */
    public function type($type = null)
    {
        if (is_null($type))
            $type = config("filemanager.type");

        $config = filemanager_config($type);

        /** @var BaseType $providerClass */
        $providerClass = $config['provider'];
        $providerClass = $providerClass::getInstance();
        $providerClass->setType($type)
            ->setConfig($config)
            ->fetchProperties();
        return $providerClass;
    }


    public function __call($method, $parameters)
    {
        return $this->type()->$method(...$parameters);
    }
}
