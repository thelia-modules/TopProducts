<?php

namespace TopProducts\Hook;

use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use TopProducts\TopProducts;

class TopProductsHook extends BaseHook
{
    public function addCategoryTopProductsTab(HookRenderBlockEvent $event)
    {
        $categoryId = $event->getArgument('id');

        $event->add(
            [
                'id' => 'top_products',
                'title' => $this->trans('Top products', [], TopProducts::DOMAIN_NAME),
                'content' => $this->render(
                    'top_products/top_products_tab_content.html',
                    [
                        'elementKey' => 'category',
                        'elementId' => $categoryId
                    ]
                )
            ]
        );
    }

    public function addBrandTopProductsTab(HookRenderBlockEvent $event)
    {
        $brandId = $event->getArgument('brand_id');

        $event->add(
            [
                'id' => 'top_products',
                'title' => $this->trans('Top products', [], TopProducts::DOMAIN_NAME),
                'content' => $this->render(
                    'top_products/top_products_tab_content.html',
                    [
                        'elementKey' => 'brand',
                        'elementId' => $brandId
                    ]
                )
            ]
        );
    }

    public function addTopProductsJs(HookRenderEvent $event)
    {
        $js = $this->addJS('top_products/assets/dist/js/app.js');

        $event->add($js);
    }

    public function addTopProductsCss(HookRenderEvent $event)
    {
        $css = $this->addCSS('top_products/assets/dist/css/app.css');

        $event->add($css);
    }

}