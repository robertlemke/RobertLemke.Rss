RSS / Atom Feed Generator
=========================

This is yet another RSS / Atom feed generator for PHP 5.3 and later. It works great
in combination with TYPO3 Flow but should also be fine as a standalone library.

This package is composer enabled.

Example
-------

```php
<?php

$feed = new Feed();

$channel = new Channel();
$channel
  ->setTitle('All about TYPO3 Flow')
  ->setDescription($channelDescription)
  ->setFeedUri($feedUri)
  ->setWebsiteUri($websiteUri)
  ->setLanguage('en-US');

$item = new Item();
$item
  ->setTitle('My first blog post')
  ->setGuid($someUniqueIdentifier)
  ->setPublicationDate(new \DateTime())
  ->setContent($blogPostContent);

$channel->addItem($item);
$feed->addChannel($channel);

echo $feed;

```
