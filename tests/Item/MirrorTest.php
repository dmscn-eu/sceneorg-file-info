<?php

namespace DmscnTest\SceneOrg\Item;

use DmscnEu\SceneOrgFileInfo\Item\FileInfo;
use DmscnEu\SceneOrgFileInfo\Item\Mirror;
use DmscnEu\SceneOrgFileInfo\Item\Mirrors;
use PHPUnit\Framework\TestCase;

/**
 * Class MirrorTest
 *
 * @covers DmscnEu\SceneOrgFileInfo\Item\Mirror
 */
class MirrorTest extends TestCase
{

    /**
     * @test
     */
    public function testGettersAndSetters()
    {
        $fileInfo = new Mirror();

        $fileInfo->setHostname('host');
        $this->assertEquals('host', $fileInfo->getHostname());

        $fileInfo->setUrl('uri');
        $this->assertEquals('uri', $fileInfo->getUrl());
    }

}