<?php declare(strict_types=1);

namespace AppBundle\BikeMeter;

use Curl\Curl;
use SimpleXMLElement;

class DataFetcher
{
    const DATA_SOURCE_URL = 'https://geoportal-hamburg.de/geodienste_hamburg_de/HH_WMS_Radverkehrszaehlsaeulen?SERVICE=WMS&VERSION=1.3.0&REQUEST=GetFeatureInfo&FORMAT=image%2Fpng&TRANSPARENT=true&QUERY_LAYERS=zaehlstellen&t=246&zufall=0.13577735920122191&LAYERS=zaehlstellen&INFO_FORMAT=text%2Fxml&FEATURE_COUNT=1&I=50&J=50&CRS=EPSG%3A25832&STYLES=&WIDTH=101&HEIGHT=101&BBOX=566012.1849068201%2C5934123.9991770405%2C567615.5590409981%2C5935727.373311218';

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
