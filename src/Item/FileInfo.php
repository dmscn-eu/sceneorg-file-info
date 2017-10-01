<?php

namespace DmscnEu\SceneOrgFileInfo\Item;

/**
 * Class FileInfo
 */
class FileInfo
{

    /**
     * @var int
     */
    protected $id = 0;

    /**
     * @var int
     */
    protected $filesize = 0;

    /**
     * @var \DateTime
     */
    protected $filedate;

    /**
     * @var int
     */
    protected $totalDownloadcount = 0;

    /**
     * @var Mirrors
     */
    protected $mirrors;

    /**
     * @var int|null
     */
    protected $pouetId = null;

    /**
     * @var string|null
     */
    protected $pouetLink = null;

    /**
     * @var string|null
     */
    protected $pouetScreenshotUrl = null;

    /**
     * @var string|null
     */
    protected $descriptionText = null;

    /**
     * FileInfo constructor.
     */
    public function __construct()
    {
        $this->filedate = new \DateTime();
        $this->mirrors = new Mirrors();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return FileInfo
     */
    public function setId(int $id): FileInfo
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getFilesize(): int
    {
        return $this->filesize;
    }

    /**
     * @param int $filesize
     * @return FileInfo
     */
    public function setFilesize(int $filesize): FileInfo
    {
        $this->filesize = $filesize;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFiledate(): \DateTime
    {
        return $this->filedate;
    }

    /**
     * @param \DateTime $filedate
     * @return FileInfo
     */
    public function setFiledate(\DateTime $filedate): FileInfo
    {
        $this->filedate = $filedate;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalDownloadcount(): int
    {
        return $this->totalDownloadcount;
    }

    /**
     * @param int $totalDownloadcount
     * @return FileInfo
     */
    public function setTotalDownloadcount(int $totalDownloadcount): FileInfo
    {
        $this->totalDownloadcount = $totalDownloadcount;
        return $this;
    }

    /**
     * @return Mirrors
     */
    public function getMirrors(): Mirrors
    {
        return $this->mirrors;
    }

    /**
     * @param Mirrors $mirrors
     * @return FileInfo
     */
    public function setMirrors(Mirrors $mirrors): FileInfo
    {
        $this->mirrors = $mirrors;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPouetId()
    {
        return $this->pouetId;
    }

    /**
     * @param int|null $pouetId
     * @return FileInfo
     */
    public function setPouetId($pouetId)
    {
        $this->pouetId = $pouetId;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPouetLink()
    {
        return $this->pouetLink;
    }

    /**
     * @param null|string $pouetLink
     * @return FileInfo
     */
    public function setPouetLink($pouetLink)
    {
        $this->pouetLink = $pouetLink;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPouetScreenshotUrl()
    {
        return $this->pouetScreenshotUrl;
    }

    /**
     * @param null|string $pouetScreenshotUrl
     * @return FileInfo
     */
    public function setPouetScreenshotUrl($pouetScreenshotUrl)
    {
        $this->pouetScreenshotUrl = $pouetScreenshotUrl;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getDescriptionText()
    {
        return $this->descriptionText;
    }

    /**
     * @param null|string $descriptionText
     * @return FileInfo
     */
    public function setDescriptionText($descriptionText)
    {
        $this->descriptionText = $descriptionText;
        return $this;
    }
}
