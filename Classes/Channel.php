<?php
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
    protected $imageUri;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var array
     */
    protected $items = array();

    /**
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param string $feedUri
     * @return void
     */
    public function setFeedUri($feedUri)
    {
        $this->feedUri = $feedUri;
    }

    /**
     * @param string $websiteUri
     * @return void
     */
    public function setWebsiteUri($websiteUri)
    {
        $this->websiteUri = $websiteUri;
    }

    /**
     * @param string $language
     * @return void
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Adds a new item to this channel
     *
     * @param Item $item An item
     * @return void
     */
    public function addItem(Item $item)
    {
        $this->items[] = $item;
    }

    /**
     * @return \SimpleXMLElement
     */
    public function asXML()
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