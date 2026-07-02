<?php

namespace TopProducts\Hook;

use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use TopProducts\TopProducts;

class TopProductsHook extends BaseHook
{
    public static function getSubscribedHooks(): array
    {
        return [
            'category.tab' => [
                ['type' => 'back', 'method' => 'addCategoryTopProductsTab'],
            ],
            'brand.tab' => [
                ['type' => 'back', 'method' => 'addBrandTopProductsTab'],
            ],
            'category.edit-js' => [
                ['type' => 'back', 'method' => 'addTopProductsJs'],
            ],
            'brand.edit-js' => [
                ['type' => 'back', 'method' => 'addTopProductsJs'],
            ],
            'main.head-css' => [
                ['type' => 'back', 'method' => 'addTopProductsCss'],
            ],
        ];
    }

    public function addCategoryTopProductsTab(HookRenderBlockEvent $event): void
    {
        $categoryId = $event->getArgument('id');

        $event->add(
            [
                'id' => 'top_products',
                'title' => $this->trans('Top products', [], TopProducts::DOMAIN_NAME),
                'content' => $this->render(
                    'top_products_tab_content.html.twig',
                    [
                        'elementKey' => 'category',
                        'elementId' => $categoryId
                    ]
                )
            ]
        );
    }

    public function addBrandTopProductsTab(HookRenderBlockEvent $event): void
    {
        $brandId = $event->getArgument('brand_id');

        $event->add(
            [
                'id' => 'top_products',
                'title' => $this->trans('Top products', [], TopProducts::DOMAIN_NAME),
                'content' => $this->render(
                    'top_products_tab_content.html.twig',
                    [
                        'elementKey' => 'brand',
                        'elementId' => $brandId
                    ]
                )
            ]
        );
    }
}
