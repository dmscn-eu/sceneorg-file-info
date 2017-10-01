<?php

namespace DmscnTest\SceneOrg\Item;

use DmscnEu\SceneOrgFileInfo\Item\FileInfo;
use DmscnEu\SceneOrgFileInfo\Item\Mirror;
use DmscnEu\SceneOrgFileInfo\Item\Mirrors;
use PHPUnit\Framework\TestCase;

/**
 * Class FileInfoTest
 *
 * @covers DmscnEu\SceneOrgFileInfo\Item\FileInfo
 */
class FileInfoTest extends TestCase
{

    /**
     * @test
     */
    public function testGettersAndSetters()
    {
        $fileInfo = new FileInfo();

        $fileInfo->setTotalDownloadcount(9);
        $this->assertEquals(9, $fileInfo->getTotalDownloadcount());

        $fileInfo->setPouetScreenshotUrl('psu');
        $this->assertEquals('psu', $fileInfo->getPouetScreenshotUrl());

        $fileInfo->setPouetLink('pl');
        $this->assertEquals('pl', $fileInfo->getPouetLink());

        $fileInfo->setId(8);
        $this->assertEquals(8, $fileInfo->getId());

        $fileInfo->setDescriptionText('desc');
        $this->assertEquals('desc', $fileInfo->getDescriptionText());

        $fileInfo->setFiledate($dt = new \DateTime());
        $this->assertEquals($dt, $fileInfo->getFiledate());

        $fileInfo->setFilesize(28);
        $this->assertEquals(28, $fileInfo->getFilesize());

        $fileInfo->setPouetId(23);
        $this->assertEquals(23, $fileInfo->getPouetId());

        $fileInfo->setMirrors($mirrors = new Mirrors());
        $this->assertEquals($mirrors, $fileInfo->getMirrors());
    }
}
