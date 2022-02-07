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
 * @Neos\Flow\Annotations\Proxy(false)
 */
class SimpleXMLElement extends \SimpleXMLElement
{
    /**
     * Adds a new child node - and replaces "&" by "&amp;" on the way ...
     *
     * @param string $name Name of the tag
     * @param string $value The tag value, if any
     * @param string $namespace The tag namespace, if any
     * @return \SimpleXMLElement
     */
    public function addChild($name, $value = null, $namespace = null)
    {
        return parent::addChild($name, ($value !== null ? str_replace('&', '&amp;', $value) : null), $namespace);
    }

    /**
     * Adds a new attribute - and replace "&" by "&amp;" on the way ...
     *
     * @param string $name Name of the attribute
     * @param string $value The value to set, if any
     * @param string $namespace The namespace, if any
     * @return void
     */
    public function addAttribute($name, $value = null, $namespace = null)
    {
        parent::addAttribute($name, ($value !== null ? str_replace('&', '&amp;', $value) : null), $namespace);
    }

    /**
     * Pretty much like addChild() but wraps the value in CDATA
     *
     * @param string $name tag name
     * @param string $value tag value
     * @param ?string $namespace The tag namespace, if any
     * @return \SimpleXMLElement
     */
    public function addCdataChild(string $name, string $value, string $namespace = null): SimpleXMLElement
    {
        $child = $this->addChild($name, null, $namespace);
        $child->setChildCdataValue($value);
        return $child;
    }

    /**
     * Sets a cdata value for this child
     *
     * @param string $value The value to be enclosed in CDATA
     * @return void
     */
    private function setChildCdataValue(string $value): void
    {
        $domNode = dom_import_simplexml($this);
        $domNode->appendChild($domNode->ownerDocument->createCDATASection($value));
    }

}
