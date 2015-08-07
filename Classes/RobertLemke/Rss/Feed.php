<?php
namespace RobertLemke\Rss;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "RobertLemke.Rss".       *
 *                                                                        */

/**
 * An RSS Feed
 */
class Feed {

	/**
	 * @var array<Channel>
	 */
	protected $channels = array();

	/**
	 * Adds a new channel to this feed
	 *
	 * @param Channel $channel
	 * @return Feed
	 */
	public function addChannel(Channel $channel) {
		$this->channels[] = $channel;
		return $this;
	}

	/**
	 * Renders the full XML output for this RSS feed
	 *
	 * @return string
	 */
	public function render() {
		$xml = new SimpleXMLElement('
			<?xml version="1.0" encoding="UTF-8" ?>
			<rss version="2.0"
				xmlns:content="http://purl.org/rss/1.0/modules/content/"
				xmlns:wfw="http://wellformedweb.org/CommentAPI/"
				xmlns:dc="http://purl.org/dc/elements/1.1/"
				xmlns:atom="http://www.w3.org/2005/Atom"
				xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
				xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
			/>
			', LIBXML_NOERROR|LIBXML_ERR_NONE|LIBXML_ERR_FATAL);

		foreach ($this->channels as $channel) {
			$toDom = dom_import_simplexml($xml);
			$fromDom = dom_import_simplexml($channel->asXML());
			$toDom->appendChild($toDom->ownerDocument->importNode($fromDom, TRUE));
		}

		$dom = new \DOMDocument('1.0', 'UTF-8');
		$dom->appendChild($dom->importNode(dom_import_simplexml($xml), TRUE));
		$dom->formatOutput = TRUE;

		return $dom->saveXML();
	}
}