<?php declare(strict_types=1);

namespace AppBundle\BikeMeter;

use Curl\Curl;
use SimpleXMLElement;

class DataFetcher
{
    const DATA_SOURCE_URL = 'https://geodienste.hamburg.de/HH_WFS_Radverkehrszaehlsaeulen?SERVICE=WFS&VERSION=1.1.0&REQUEST=GetFeature&typename=app:zaehlsaeulen';

    /** @var Curl $curl */
    protected $curl;

    /** @var SimpleXMLElement $xmlRootElement */
    protected $xmlRootElement;

    public function __construct()
    {
        $this->curl = new Curl();
    }

    public function fetch(): DataFetcher
    {
        $this->curl->get(self::DATA_SOURCE_URL);

        $this->xmlRootElement = $this->curl->response;

        return $this;
    }

    public function getXmlRootElement(): SimpleXMLElement
    {
        return $this->xmlRootElement;
    }
}
