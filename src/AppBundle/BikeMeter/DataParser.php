<?php

namespace AppBundle\BikeMeter;


use SimpleXMLElement;

class DataParser
{
    /** @var SimpleXMLElement $xmlRootElement */
    protected $xmlRootElement;

    public function setXmlRootElement(SimpleXMLElement $element): DataParser
    {
        $this->xmlRootElement = $element;

        return $this;
    }
}
