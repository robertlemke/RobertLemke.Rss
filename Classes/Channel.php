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
 * An RSS Channel
 */
class Channel
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $feedUri;

    /**
     * @var string
     */
    protected $websiteUri;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * @param string $description
     * @return Channel
     */
    public function setDescription(string $description): Channel
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string $title
     * @return Channel
     */
    public function setTitle(string $title): Channel
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $feedUri
     * @return Channel
     */
    public function setFeedUri(string $feedUri): Channel
    {
        $this->feedUri = $feedUri;
        return $this;
    }

    /**
     * @param string $websiteUri
     * @return Channel
     */
    public function setWebsiteUri(string $websiteUri): Channel
    {
        $this->websiteUri = $websiteUri;
        return $this;
    }

    /**
     * @param string $language
     * @return Channel
     */
    public function setLanguage(string $language): Channel
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Adds a new item to this channel
     *
     * @param Item $item An item
     * @return Channel
     */
    public function addItem(Item $item): Channel
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * @return \SimpleXMLElement
     * @throws \Exception
     */
    public function asXML(): \SimpleXMLElement
    {
        $date = new \DateTime('now', new \DateTimeZone('GMT'));
        $nowFormatted = $date->format('D, d M Y H:i:s') . ' GMT';

        $xml = new SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8" ?>
			<channel
				xmlns:content="http://purl.org/rss/1.0/modules/content/"
				xmlns:wfw="http://wellformedweb.org/CommentAPI/"
				xmlns:dc="http://purl.org/dc/elements/1.1/"
				xmlns:atom="http://www.w3.org/2005/Atom"
				xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
				xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
			/>',
            LIBXML_NOERROR | LIBXML_ERR_NONE | LIBXML_ERR_FATAL
        );
        $xml->addChild('title', $this->title);

        $linkNode = $xml->addChild('link', null, 'http://www.w3.org/2005/Atom');
        $linkNode->addAttribute('href', $this->feedUri);
        $linkNode->addAttribute('rel', 'self');
        $linkNode->addAttribute('type', 'application/rss+xml');

        $xml->addChild('link', $this->websiteUri);
        $xml->addChild('description', $this->description);
        $xml->addChild('lastBuildDate', $nowFormatted);
        $xml->addChild('language', $this->language);

        foreach ($this->items as $item) {
            $toDom = dom_import_simplexml($xml);
            $fromDom = dom_import_simplexml($item->asXML());
            $toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));
        }

        return $xml;
    }
}
