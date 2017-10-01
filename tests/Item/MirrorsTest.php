<?php

namespace DmscnTest\SceneOrg\Item;

use DmscnEu\SceneOrgFileInfo\Item\Mirror;
use DmscnEu\SceneOrgFileInfo\Item\Mirrors;
use PHPUnit\Framework\TestCase;

/**
 * Class MirrorsTest
 *
 * @covers DmscnEu\SceneOrgFileInfo\Item\Mirrors
 */
class MirrorsTest extends TestCase
{

    /**
     * @test
     */
    public function testEmpty() {
        $mirrors = new Mirrors();
        $this->assertEquals(0, $mirrors->count());
    }

    /**
     * @test
     */
    public function testEntry() {
        $mirrors = new Mirrors();
        $mirrors->add($mirrorItem = new Mirror());
        $this->assertEquals(1, $mirrors->count());
        foreach ($mirrors as $mirror) {
            $this->assertInstanceOf(Mirror::class, $mirror);
            $this->assertEquals($mirrorItem, $mirror);
        }
    }
}
