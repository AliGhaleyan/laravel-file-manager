<?php
/**
 * Created by SERJIK
 */

namespace Serjik\FileManager\src;

use Serjik\FileManager\BaseType;

class File
{
    public function type($type = null)
    {
        if (is_null($type))
            $type = config("filemanager.type");

        $typeConfig = config("filemanager.types.{$type}");

        /** @var BaseType $providerClass */
        $providerClass = $typeConfig['provider'];
        $providerClass = $providerClass::getInstance();
        $providerClass->setType($type);
        return $providerClass;
    }

    public function __call($method, $parameters)
    {
        return $this->type()->$method(...$parameters);
    }
}
