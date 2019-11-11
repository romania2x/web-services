<?php

namespace App\MessageHandler\Crawler\DataGovRo;

use App\Entity\OpenData\Source;

trait FileSystemAwareTrait
{
    /**
     * @var string
     */
    protected $downloadCacheDir;

    /**
     * @param string $location
     */
    public function setProjectDir(string $location)
    {
        $this->downloadCacheDir = "$location/download_cache/";
    }

    /**
     * @param Source $source
     * @return string
     */
    protected function generateLocalFilePath(Source $source)
    {
        return $this->downloadCacheDir . 'resources_' . md5($source->getUrl());
    }
}
