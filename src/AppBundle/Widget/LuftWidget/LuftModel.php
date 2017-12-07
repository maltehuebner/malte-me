<?php

namespace AppBundle\Widget\LuftWidget;

use AppBundle\Widget\WidgetDataInterface;

class LuftModel implements WidgetDataInterface
{
    protected $dataList = [];

    public function addData(LuftDataModel $luftData): LuftModel
    {
        $this->dataList[] = $luftData;

        return $this;
    }

    public function getDataList(): array
    {
        return $this->dataList;
    }

    public function getIdentifier(): string
    {
        return 'luft';
    }
}
