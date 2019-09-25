<?php
declare(strict_types=1);

namespace RobertLemke\Rss;

/*
 * This file is part of the RobertLemke.Rss package.
 *
 * (c) Robert Lemke
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

/**
 * An item of an RSS channel
 */
class Item
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $itemLink;

    /**
     * @var string
     */
    protected $commentsLink;

    /**
     * @var \DateTime
     */
    protected $publicationDate;

    /**
     * @var string
     */
    protected $creator;

    /**
     * @var string
     */
    protected $guid;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var array<string>
     */
    protected $categories = [];

    /**
     * Can be an array of either strings or arrays (with indexes "category" and "domain").
     *
     * @param array $categories
     * @return Item
     */
    public function setCategories(array $categories): Item
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param string $commentsLink
     * @return Item
     */
    public function setCommentsLink(string $commentsLink): Item
    {
        $this->commentsLink = $commentsLink;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommentsLink(): string
    {
        return $this->commentsLink;
    }

    /**
     * @param string $content
     * @return Item
     */
    public function setContent(string $content): Item
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $creator
     * @return Item
     */
    public function setCreator(string $creator): Item
    {
        $this->creator = $creator;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreator(): string
    {
        return $this->creator;
    }

    /**
     * @param string $description
     * @return Item
     */
    public function setDescription(string $description): Item
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $guid
     * @return Item
     */
    public function setGuid(string $guid): Item
    {
        $this->guid = $guid;
        return $this;
    }

    /**
     * @return string
     */
    public function getGuid(): string
    {
        return $this->guid;
    }

    /**
     * @param string $itemLink
     * @return Item
     */
    public function setItemLink(string $itemLink): Item
    {
        $this->itemLink = $itemLink;
        return $this;
    }

    /**
     * @return string
     */
    public function getItemLink(): string
    {
        return $this->itemLink;
    }

    /**
     * @param \DateTime $publicationDate
     * @return Item
     */
    public function setPublicationDate(\DateTime $publicationDate = null): Item
    {
        $this->publicationDate = $publicationDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPublicationDate(): \DateTime
    {
        return $this->publicationDate;
    }

    /**
     * @param string $title
     * @return Item
     */
    public function setTitle(string $title): Item
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return \SimpleXMLElement
     */
    public function asXml(): \SimpleXMLElement
    {
        $xml = new SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8" ?>
            <item
                xmlns:content="http://purl.org/rss/1.0/modules/content/"
                xmlns:wfw="http://wellformedweb.org/CommentAPI/"
                xmlns:dc="http://purl.org/dc/elements/1.1/"
                xmlns:atom="http://www.w3.org/2005/Atom"
            />',
            LIBXML_NOERROR | LIBXML_ERR_NONE | LIBXML_ERR_FATAL
        );

        $node = $xml->addChild('guid', $this->guid);
        $node->addAttribute('isPermaLink', 'false');

        $xml->addChild('title', $this->title);
        $xml->addChild('link', $this->itemLink);

        if ($this->commentsLink !== null) {
            $xml->addChild('comments', $this->commentsLink);
        }
        if ($this->publicationDate !== null) {
            $xml->addChild('pubDate', $this->publicationDate->format('D, d M Y H:i:s') . ' GMT');
        }
        if ($this->creator !== null) {
            $xml->addChild('creator', $this->creator, 'http://purl.org/dc/elements/1.1/');
        }
        if ($this->description !== null) {
            $xml->addCdataChild('description', $this->description);
        }
        if ($this->content !== null) {
            $xml->addCdataChild('encoded', $this->content, 'http://purl.org/rss/1.0/modules/content/');
        }
        foreach ($this->categories as $category) {
            if (is_string($category)) {
                $xml->addCdataChild('category', $category);
            } else {
                $categoryElement = $xml->addCdataChild('category', $category['category']);
                if ($category['domain']) {
                    $categoryElement->addAttribute('domain', $category['domain']);
                }
            }
        }

        return $xml;
    }
}
