<?php

namespace TopProducts\Model;

use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Model\Tools\PositionManagementTrait;
use TopProducts\Model\Base\TopProduct as BaseTopProduct;

class TopProduct extends BaseTopProduct
{
    use PositionManagementTrait;

    /**
     * @inheritdoc
     */
    protected function addCriteriaToPositionQuery(TopProductQuery $query)
    {
        $query->filterBySelectionCode($this->getSelectionCode())
            ->filterByElementKey($this->getElementKey())
            ->filterByElementId($this->getElementId());
    }

    public function preDelete(ConnectionInterface $con = null)
    {
        $this->reorderBeforeDelete(
            array(
                "selection_code" => $this->getSelectionCode(),
                "element_id" => $this->getElementId(),
                "element_key" => $this->getElementKey()
            )
        );

        return true;
    }
}
