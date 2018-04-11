<?php declare(strict_types=1);

namespace AppBundle\BikeMeter;

use AppBundle\Entity\BikeMeter;
use AppBundle\Entity\BikeMeterData;
use SimpleXMLElement;

class DataParser
{
    /** @var SimpleXMLElement $xmlRootElement */
    protected $xmlRootElement;

    /** @var array $dataList */
    protected $dataList = [];

    /** @var BikeMeter $bikeMeter */
    protected $bikeMeter;

    /** @var \DateTimeZone $timezone */
    protected $timezone;

    public function __construct()
    {

    }

    public function setBikeMeter(BikeMeter $bikeMeter): DataParser
    {
        $this->bikeMeter = $bikeMeter;

        return $this;
    }

    public function setTimezone(\DateTimeZone $timezone): DataParser
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function setXmlRootElement(SimpleXMLElement $element): DataParser
    {
        $this->xmlRootElement = $element;
        $this->xmlRootElement->registerXPathNamespace('gml', 'http://www.opengis.net/gml');
        $this->xmlRootElement->registerXPathNamespace('app', 'http://www.deegree.org/app');

        return $this;
    }

    public function parse(): DataParser
    {
        $tagesLinie = $this->xmlRootElement->xpath('//app:tageslinie');

        $this->dataList = $this->parseTagesLinie(array_pop($tagesLinie));

        return $this;
    }

    protected function parseTagesLinie(SimpleXMLElement $tagesLinie): array
    {
        $list = [];

        $content = $tagesLinie->__toString();

        $parts = explode('|', $content);

        foreach ($parts as $data) {
            list($date, $time, $value) = explode(',', $data);

            $dateTimeSpec = sprintf('%s %s', $date, $time);
            $dateTime = new \DateTime($dateTimeSpec, $this->timezone);

            $data = new BikeMeterData();
            $data
                ->setMeter($this->bikeMeter)
                ->setDateTime($dateTime)
                ->setValue(intval($value));

            $list[$dateTime->format('U')] = $data;
        }

        return $list;
    }

    public function getDataList(): array
    {
        return $this->dataList;
    }
}
