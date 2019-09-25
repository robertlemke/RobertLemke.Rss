RSS / Atom Feed Generator
=========================

This is yet another RSS / Atom feed generator for PHP 7 and later. It works great
in combination with Flow but should also be fine as a standalone library.

This package is composer enabled.

Example
-------

```php
<?php

$feed = new \RobertLemke\Rss\Feed();

$channel = new \RobertLemke\Rss\Channel();
$channel
  ->setTitle('All about Neos Flow')
  ->setDescription($channelDescription)
  ->setFeedUri($feedUri)
  ->setWebsiteUri($websiteUri)
  ->setLanguage('en-US');

$item = new \RobertLemke\Rss\Item();
$item
  ->setTitle('My first blog post')
  ->setGuid($someUniqueIdentifier)
  ->setPublicationDate(new DateTime())
  ->setContent($blogPostContent);

$channel->addItem($item);
$feed->addChannel($channel);

echo $feed->render();

```
