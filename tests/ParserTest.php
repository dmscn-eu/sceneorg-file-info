<?php

namespace DmscnTest\SceneOrg;

use DmscnEu\SceneOrgFileInfo\Exceptions\UnableToParseException;
use PHPUnit\Framework\TestCase;
use DmscnEu\SceneOrgFileInfo\Parser;

/**
 * Class ParserTest
 */
class ParserTest extends TestCase
{

    public function testWithHerbst77()
    {
        $parser = new Parser(file_get_contents(__DIR__ . '/data/herbst77.html'));

        $this->assertEquals(104127, $parser->getId());
        $this->assertEquals(6807049, $parser->getFilesize());
        $this->assertEquals(null, $parser->getFiledate());
        $this->assertEquals(5678, $parser->getDownloadCount());
        $this->assertInternalType('array', $parser->getMirrors());
        $this->assertCount(11, $parser->getMirrors());
        $this->assertContains('raytrazer', $parser->getFileIdDiz());
        $this->assertEquals('http://www.pouet.net/prod.php?which=318', $parser->getPouetLink());
        $this->assertEquals(318, $parser->getPouetId());
        $this->assertEquals('http://content.pouet.net/files/screenshots/00000/00000318.jpg', $parser->getScreenshotUrl());
    }

    public function testWithFr25()
    {
        $parser = new Parser(file_get_contents(__DIR__ . '/data/fr25.html'));

        $this->assertEquals(802549, $parser->getId());
        $this->assertEquals(8608196, $parser->getFilesize());
        $this->assertEquals((new \DateTime('2016-07-05 05:45:02'))->getTimestamp(), $parser->getFiledate()->getTimestamp());
        $this->assertEquals(3548, $parser->getDownloadCount());
        $this->assertInternalType('array', $parser->getMirrors());
        $this->assertCount(11, $parser->getMirrors());
        $this->assertContains('fiver2', $parser->getFileIdDiz());
        $this->assertEquals('http://content.pouet.net/files/screenshots/00009/00009450.jpg', $parser->getScreenshotUrl());
    }

    public function testWithSpok()
    {

        $parser = new Parser(file_get_contents(__DIR__ . '/data/spok.html'));

        $this->assertEquals(6663536, $parser->getId());
        $this->assertEquals(0, $parser->getDownloadCount());
        $this->assertInternalType('array', $parser->getMirrors());
        $this->assertCount(3, $parser->getMirrors());
        $this->assertStringStartsWith('Full name of prod:', $parser->getFileIdDiz());
    }

    /**
 * @test
 */
    public function testWithEmptyDocument()
    {
        $parser = new Parser('<html></html>');

        $this->assertNull($parser->getFilesize());
        $this->assertNull($parser->getDownloadCount());
        $this->assertNull($parser->getFiledate());
        $this->assertNull($parser->getDownloadCount());
        $this->assertNull($parser->getFileIdDiz());
        $this->assertNull($parser->getId());
        $this->assertNull($parser->getPouetId());
        $this->assertNull($parser->getScreenshotUrl());
    }

    /**
     * @test
     * @expectedException DmscnEu\SceneOrgFileInfo\Exceptions\UnableToParseException
     */
    public function testWithEmptyDocumentThrowsException()
    {
        $parser = new Parser('<html></html>');

        $parser->getFileinfo();
    }

    public function testWithCorruptData()
    {
        $parser = new Parser('
        <section id=\'fileinfo\' data-fileid=\'802549\'>
            <div>
                <dl>
                    <dt>File size:</dt>
                    <dd>8 608 196 by-tes (8.21M)</dd>
                    <dt>File date:</dt>
                    <dd>2016-07-05 05:45:02</dd>
                    <dt>Download count:</dt>
                    <dd>alltime: 3 54</dd>
                    </dl></div></section>
        <section id=\'screenshot\'><h3><a href="http://www.pouet.net/#index">pouet.net</a></h3></section>      
                   ');

        $this->assertNull($parser->getFilesize());
        $this->assertNull($parser->getDownloadCount());
        $this->assertNull($parser->getPouetId());
    }
}
