<?php

namespace DmscnTest\SceneOrg;

use DmscnEu\SceneOrgFileInfo\Item\FileInfo;
use DmscnEu\SceneOrgFileInfo\Service;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Message\ResponseInterface;
use \Mockery;

/**
 * Class ServiceTest
 *
 */
class ServiceTest extends TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function testGetFileInfoWith404()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->andReturn(404)->once();

        $client = Mockery::mock(Client::class);
        $client->shouldReceive('get')->andReturn($response)->once();

        $service = new Service($client);
        $service->getFileInfo('/404');
    }

    /**
     * @test
     */
    public function testGetFileInfoWithContent()
    {
        $body = Mockery::mock();
        $body->shouldReceive('getContents')->andReturn(file_get_contents(__DIR__ . '/data/fr25.html'))->once();

        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->andReturn(200)->once();
        $response->shouldReceive('getBody')->andReturn($body)->once();

        $client = Mockery::mock(Client::class);
        $client->shouldReceive('get')->andReturn($response)->once();

        $service = new Service($client);
        $fileInfo = $service->getFileInfo('/test/123.zip');

        $this->assertInstanceOf(FileInfo::class, $fileInfo);
        $this->assertEquals(802549, $fileInfo->getId());
        $this->assertEquals(11, $fileInfo->getMirrors()->count());
        $this->assertContains('scene.org', $fileInfo->getMirrors()->getIterator()->current()->getHostname());
        $this->assertContains('scene.org', $fileInfo->getMirrors()->getIterator()->current()->getUrl());
    }

    /**
     * @test
     */
    public function testGetFileInfoWithCacheAndMiss() {
        $body = Mockery::mock();
        $body->shouldReceive('getContents')->andReturn(file_get_contents(__DIR__ . '/data/fr25.html'))->once();

        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->andReturn(200)->once();
        $response->shouldReceive('getBody')->andReturn($body)->once();

        $client = Mockery::mock(Client::class);
        $client->shouldReceive('get')->andReturn($response)->once();

        $cacheItem = Mockery::mock(CacheItemInterface::class);
        $cacheItem->shouldReceive('isHit')->andReturn(false)->once();
        $cacheItem->shouldReceive('set')->andReturn(true)->once();

        $cache = Mockery::mock(CacheItemPoolInterface::class);
        $cache->shouldReceive('getItem')->andReturn($cacheItem)->once();
        $cache->shouldReceive('save')->andReturn(true)->once();

        $service = new Service($client);
        $service->setCache($cache);
        $fileInfo = $service->getFileInfo('/test/123.zip');

        $this->assertInstanceOf(FileInfo::class, $fileInfo);
        $this->assertEquals(802549, $fileInfo->getId());
    }

    /**
     * @test
     */
    public function testGetFileInfoWithCacheAndHit() {
        $client = Mockery::mock(Client::class);

        $cacheItem = Mockery::mock(CacheItemInterface::class);
        $cacheItem->shouldReceive('isHit')->andReturn(true)->once();
        $cacheItem->shouldReceive('get')->andReturn(file_get_contents(__DIR__ . '/data/fr25.html'))->once();

        $cache = Mockery::mock(CacheItemPoolInterface::class);
        $cache->shouldReceive('getItem')->andReturn($cacheItem)->once();

        $service = new Service($client);
        $service->setCache($cache);
        $fileInfo = $service->getFileInfo('/test/123.zip');

        $this->assertInstanceOf(FileInfo::class, $fileInfo);
        $this->assertEquals(802549, $fileInfo->getId());
    }

    /**
     * @test
     */
    public function testGetBaseUrl()
    {
        $client = $this->createMock('GuzzleHttp\Client');
        $service = new Service($client);

        $uri = 'ftp://ftp.de.scene.org/pub/parties/2005/evoke05/misc/dl_music_favorite_astronaut(evoke2005)_final.zip';
        $this->assertEquals('/parties/2005/evoke05/misc/dl_music_favorite_astronaut(evoke2005)_final.zip', $service->getBaseUrl($uri));

        $uri = 'http://www.scene.org/file.php?file=%2Fparties%2F1995%2Ffallas95%2Finfo%2Ffp95_fix.zip&fileinfo';
        $this->assertEquals('/parties/1995/fallas95/info/fp95_fix.zip', $service->getBaseUrl($uri));

        $uri = 'http://de.scene.org/pub/parties/2014/atparty14/results.txt';
        $this->assertEquals('/parties/2014/atparty14/results.txt', $service->getBaseUrl($uri));

        $uri = 'http://files.scene.org/get:nl-ftp/parties/2016/evoke16/demo/psa_theycalledit.zip';
        $this->assertEquals('/parties/2016/evoke16/demo/psa_theycalledit.zip', $service->getBaseUrl($uri));
    }
}
