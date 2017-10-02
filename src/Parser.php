<?php

namespace DmscnEu\SceneOrgFileInfo;

use DmscnEu\SceneOrgFileInfo\Exceptions\UnableToParseException;
use DmscnEu\SceneOrgFileInfo\Item\FileInfo;
use DmscnEu\SceneOrgFileInfo\Item\Mirror;
use \DOMDocument;

/**
 * Class Parser
 */
class Parser
{

    const FILEINFO_POSITION_FILESIZE = 1;

    const FILEINFO_POSITION_FILEDATE = 2;

    const FILEINFO_POSITION_DOWNLOADCOUNT = 3;

    /**
     * @var \DOMDocument
     */
    private $doc;

    /**
     * @var \DOMXPath
     */
    private $xpath;

    /**
     * Parser constructor.
     * @param $html
     */
    public function __construct($html)
    {
        $this->doc = new DOMDocument();
        $restore = libxml_use_internal_errors(true);
        $this->doc->loadHTML($html);
        #libxml_clear_errors();
        libxml_use_internal_errors($restore);

        $this->xpath = new \DOMXPath($this->doc);
    }

    /**
     * @return int|null
     */
    public function getFilesize()
    {
        $content = $this->getFileinfoValue(self::FILEINFO_POSITION_FILESIZE);
        if ($content === null) {
            return null;
        }

        if (!preg_match('/([0-9 ]+) bytes/', $content, $matches)) {
            return null;
        }

        return (int)str_replace(' ', '', $matches[1]);
    }

    /**
     * @return \DateTime|null
     */
    public function getFiledate()
    {
        $content = $this->getFileinfoValue(self::FILEINFO_POSITION_FILEDATE);
        if ($content === null) {
            return null;
        }

        if ($content === '1970-01-01 01:00:00') {
            return null;
        }

        return new \DateTime($content);
    }

    /**
     * @return int|null
     */
    public function getDownloadCount()
    {
        $content = $this->getFileinfoValue(self::FILEINFO_POSITION_DOWNLOADCOUNT);
        if ($content === null) {
            return null;
        }

        if (!preg_match('/all-time: ([0-9 ]+)/', $content, $matches)) {
            return null;
        }

        return (int)str_replace(' ', '', $matches[1]);
    }


    /**
     * @return array
     */
    public function getMirrors()
    {
        $result = [];

        $mirrorNodes = $this->xpath->query('//ul[@id="mirrors"]/li[not(@id) or @id!="mainDownload"]/a');
        foreach ($mirrorNodes as $mirrorNode) {
            /* @var $mirrorNode \DOMElement */
            $result[$mirrorNode->nodeValue] = $mirrorNode->getAttribute('href');
        }

        return $result;
    }

    /**
     * @return string|null
     */
    public function getFileIdDiz()
    {
        $dizNodes = $this->xpath->query('//section[@id="preview"]/pre');
        if ($dizNodes->length === 0) {
            return null;
        }

        return $dizNodes->item(0)->nodeValue;
    }

    /**
     * @return null|int
     */
    public function getId()
    {
        $fileIdAttrs = $this->xpath->query('//@data-fileid');
        if ($fileIdAttrs->length === 0) {
            return null;
        }

        return (int)$fileIdAttrs->item(0)->nodeValue;
    }

    /**
     * @return null|string
     */
    public function getPouetLink()
    {
        $linkNodes = $this->xpath->query('//section[@id="screenshot"]/h3/a/@href');
        if ($linkNodes->length === 0) {
            return null;
        }

        return $linkNodes->item(0)->nodeValue;
    }

    /**
     * @return null|int
     */
    public function getPouetId()
    {
        $link = $this->getPouetLink();
        if (!$link) {
            return null;
        }

        if (!preg_match('/which=([0-9]+)$/', $link, $matches)) {
            return null;
        }

        return $matches[1];
    }

    /**
     * @return null|string
     */
    public function getScreenshotUrl()
    {
        $linkNodes = $this->xpath->query('//section[@id="screenshot"]/img/@src');
        if ($linkNodes->length === 0) {
            return null;
        }

        return $linkNodes->item(0)->nodeValue;
    }

    /**
     * @return FileInfo
     * @throws UnableToParseException
     */
    public function getFileinfo()
    {
        $fileInfo = new FileInfo();

        if (!$this->getId()) {
            throw new UnableToParseException('Unable to retrieve id from document');
        }

        $fileInfo->setId($this->getId());
        $fileInfo->setFilesize($this->getFilesize());
        if ($this->getFiledate()) {
            $fileInfo->setFiledate($this->getFiledate());
        }
        $fileInfo->setDescriptionText($this->getFileIdDiz());
        $fileInfo->setTotalDownloadcount($this->getDownloadCount());

        if ($this->getPouetId()) {
            $fileInfo->setPouetLink($this->getPouetLink());
            $fileInfo->setPouetId($this->getPouetId());
            $fileInfo->setPouetScreenshotUrl($this->getScreenshotUrl());
        }

        foreach ($this->getMirrors() as $mirrorHostname => $mirrorUrl) {
            $mirrorObject = new Mirror();
            $mirrorObject->setHostname($mirrorHostname);
            $mirrorObject->setUrl($mirrorUrl);

            $fileInfo->getMirrors()->add($mirrorObject);
        }

        return $fileInfo;
    }

    /**
     * @param int $position
     * @return null|string
     */
    protected function getFileinfoValue($position)
    {
        $items = $this->xpath->query('//section[@id="fileinfo"]//dd[' . $position . ']');
        if ($items->length === 0) {
            return null;
        }

        return $items->item(0)->nodeValue;
    }
}
