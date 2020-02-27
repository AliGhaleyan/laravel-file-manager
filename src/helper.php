<?php
/**
 * Created by SERJIK
 */

if (!function_exists("filemanager_config")) {
    /**
     * get config
     * if pass name:
     *  check exists that
     *  if exists then return the config
     *  else if not exists name return false
     *
     * else or not pass name:
     *  return the all configs
     *
     * @param null $name
     * @return array|bool|\Illuminate\Config\Repository|mixed
     */
    function filemanager_config($type = "default", $name = null)
    {
        $config = config("filemanager.types.{$type}");
        if (isset($config["parent"])) {
            $config = array_replace(config("filemanager.types.{$config['parent']}") ?? [],
                $config);
        }
        $default = config("filemanager.types.default");
        $config = array_replace(is_array($default) ? $default : [], is_array($config) ? $config : []);

        if (is_null($name)) {
            return $config;
        } elseif (isset($config[$name])) {
            return $config[$name];
        }

        return false;
    }
}
