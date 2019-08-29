<?php

namespace TopProducts\Smarty;

use TheliaSmarty\Template\AbstractSmartyPlugin;
use TheliaSmarty\Template\SmartyPluginDescriptor;
use TopProducts\Model\TopProduct;
use TopProducts\Model\TopProductQuery;

class TopProducts extends AbstractSmartyPlugin
{
    public function getPluginDescriptors()
    {
        return [
            new SmartyPluginDescriptor('function', 'top_products', $this, 'getTopProducts'),
        ];
    }

    public function getTopProducts($data, $smarty)
    {
        $elementKey = $data['elementKey'];
        $elementId = $data['elementId'];
        $selectionCode = $data['selectionCode'];

        $topProductResults = TopProductQuery::create()
            ->filterByElementKey($elementKey)
            ->filterByElementId($elementId)
            ->filterBySelectionCode($selectionCode)
            ->find();

        $topProducts = [];

        /** @var TopProduct $topProductResult */
        foreach ($topProductResults as $topProductResult) {
            $topProducts[] = $topProductResult->getProductId();
        }

        $smarty->assign('topProducts', implode(',', $topProducts));
    }
}