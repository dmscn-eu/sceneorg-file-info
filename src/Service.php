<?php

namespace DmscnEu\SceneOrgFileInfo;

use DmscnEu\SceneOrgFileInfo\Exceptions\FileNotFoundException;
use DmscnEu\SceneOrgFileInfo\Exceptions\UnableToParseException;
use DmscnEu\SceneOrgFileInfo\Item\FileInfo;
use GuzzleHttp\ClientInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Class Service
 */
class Service
{

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var CacheItemPoolInterface
     */
    protected $cache;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
        $this->cache = null;
    }

    /**
     * @return CacheItemPoolInterface
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param CacheItemPoolInterface $cache
     */
    public function setCache(CacheItemPoolInterface $cache)
    {
        $this->cache = $cache;
    }


    /**
     * @param string $url
     * @return FileInfo
     * @throws FileNotFoundException
     * @throws UnableToParseException
     */
    public function getFileInfo($uri)
    {
        // get url
        $uri = $this->getFileinfoUrl($uri);

        // retrieve url
        $content = $this->getCachedUrlContent($uri);

        // parse
        $parser = new Parser($content);

        return $parser->getFileinfo();
    }

    /**
     * @param string $uri
     * @return mixed
     */
    protected function getCachedUrlContent($uri)
    {
        if ($this->getCache() === null) {
            return $this->getUrlContent($uri);
        } else {
            $cacheKey = md5(self::class . '-getCachedUrlContent') . '-' . md5($uri);
            $cacheItem = $this->getCache()->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            } else {
                $content = $this->getUrlContent($uri);

                $cacheItem->set($content);
                $this->getCache()->save($cacheItem);

                return $content;
            }
        }
    }

    /**
     * @param string $uri
     * @return mixed
     * @throws FileNotFoundException
     */
    protected function getUrlContent($uri)
    {
        $result = $this->client->get($uri);
        if ($result->getStatusCode() !== 200) {
            throw new FileNotFoundException('Unable to find information for ' . $uri);
        }
        return $result->getBody()->getContents();
    }

    /**
     * @param string $uri
     * @return string
     */
    protected function getFileinfoUrl($uri)
    {

        $baseUrl = $this->getBaseUrl($uri);
        // test on /

        return 'https://files.scene.org/view/' . $baseUrl;
    }

    /**
     * @param string $uri
     * @return string
     */
    public function getBaseUrl($uri)
    {
        $path = parse_url($uri, PHP_URL_PATH);

        if ($path === '/file.php') {
            parse_str(parse_url($uri, PHP_URL_QUERY), $query);

            return $query['file'];
        }

        $prefixes = [
            '/scene.org/',
            '/pub/scene.org/',
            '/mirrors/scene.org/',
            '/pub/mirrors/sceneorg/',
            '/pub/',
            '/view/',
            '#^/get:[a-z]{2}-(ftp|http)/#',
        ];

        foreach ($prefixes as $prefix) {
            if ($prefix{0} !== '#') {
                if (strpos($path, $prefix) === 0) {
                    $path = substr($path, strlen($prefix) - 1);
                }
            } else {
                if (preg_match($prefix, $path, $matches)) {
                    $path = substr($path, strlen($matches[0]) - 1);
                }
            }
        }

        return $path;
    }
}
