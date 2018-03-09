<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 07/03/2018
 * Time: 14:36
 */

namespace App\Service\Article\Warmer;


use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class YamlCacheWarmer extends CacheWarmer
{
    public function isOptional()
    {
        return false;
    }

    /**
     * @param string $cacheDir
     */
    public function warmUp($cacheDir)
    {
        try {
            $value = Yaml::parseFile(__DIR__. "/../articles.yaml");
            $this->writeCacheFile($cacheDir.'/yaml-articles.php', serialize($value['data']));
        } catch (ParseException $e) {
            printf('Unable to parse the YAML string: %s', $e->getMessage());
        }
    }


}