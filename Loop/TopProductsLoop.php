<?php

namespace TopProducts\Loop;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Core\Template\Loop\Product as ProductLoop;
use Thelia\Model\Map\ProductTableMap;
use Thelia\Model\ProductQuery;
use TopProducts\Model\Map\TopProductTableMap;

class TopProductsLoop extends ProductLoop
{
    /**
     * @return ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        $argumentCollection = parent::getArgDefinitions();

        $argumentCollection->addArguments(
            [
                Argument::createIntListTypeArgument('top_product_id'),
                Argument::createAlphaNumStringTypeArgument('top_product_element_key', null, true),
                Argument::createIntListTypeArgument('top_product_element_id', null, true),
                Argument::createAlphaNumStringTypeArgument('top_product_selection_code')
            ]
        );

        return $argumentCollection;
    }

    /**
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria|ProductQuery
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function buildModelCriteria()
    {
        /** @var ProductQuery $search */
        $search = parent::buildModelCriteria();

        $join = new Join();

        $join->addExplicitCondition(
            ProductTableMap::TABLE_NAME,
            'ID',
            null,
            TopProductTableMap::TABLE_NAME,
            'PRODUCT_ID',
            null
        );

        $join->setJoinType(Criteria::INNER_JOIN);

        $search->addJoinObject($join);

        $search->where(TopProductTableMap::ELEMENT_KEY.' = ?', $this->getTopProductElementKey(), \PDO::PARAM_STR);
        $search->where(TopProductTableMap::ELEMENT_ID.' IN (?)', implode(',', $this->getTopProductElementId()), \PDO::PARAM_STR);

        $selectionCode = $this->getTopProductSelectionCode();
        if (null !== $selectionCode) {
            $search->where(TopProductTableMap::SELECTION_CODE.' = ?', $selectionCode, \PDO::PARAM_STR);
        }

        $topProductId = $this->getTopProductId();
        if (null !== $topProductId) {
            $search->where(TopProductTableMap::ID.' = ?', $topProductId, \PDO::PARAM_INT);
        }

        $search->withColumn(TopProductTableMap::POSITION, 'top_product_position');

        $search->clearOrderByColumns();
        $search->orderBy('top_product_position', Criteria::ASC);

        return $search;
    }
}