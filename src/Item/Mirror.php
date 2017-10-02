<?php

namespace DmscnEu\SceneOrgFileInfo\Item;

/**
 * Class Mirror
 */
class Mirror
{

    /**
     * @var string
     */
    protected $hostname;

    /**
     * @var string
     */
    protected $url;

    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @param string $hostname
     * @return Mirror
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Mirror
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }
}
