<?php


namespace TopProducts\Controller;


use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\HttpFoundation\JsonResponse;
use TopProducts\Model\TopProduct;
use TopProducts\Model\TopProductQuery;

class TopProductsController extends BaseFrontController
{
    public function getProductAction($elementKey, $elementId)
    {
        $locale = $this->getSession()->getLang()->getLocale();

        $topProducts = TopProductQuery::create()
            ->filterByElementKey($elementKey)
            ->filterByElementId($elementId)
            ->find();

        $results = [];

        /** @var TopProduct $result */
        foreach ($topProducts as $topProduct) {
            if (!isset($results[$topProduct->getSelectionCode()])) {
                $results[$topProduct->getSelectionCode()] =
                    [
                        'code' => $topProduct->getSelectionCode(),
                        'topProducts' => []
                    ];
            }

            $product = $topProduct->getProduct()
                ->setLocale($locale);

            $results[$topProduct->getSelectionCode()]['topProducts'][] = [
                'id' => $topProduct->getId(),
                'position' => $topProduct->getPosition(),
                'product' => [
                    'id' => $product->getId(),
                    'title' => $product->getTitle(),
                    'reference' => $product->getRef(),
                    'visible' => $product->getVisible()
                ]
            ];
        }

        foreach ($results as $key => $result) {
            usort($results[$key]['topProducts'], function ($a, $b) {
                return $a['position'] - $b['position'];
            });
        }

        return new JsonResponse(['topProductSelections' => array_values($results)]);
    }

    public function addProductAction($elementKey, $elementId, $selectionCode)
    {
        try {
            $productId = $this->getRequest()->get('productId');

            $topProduct = (new TopProduct())
                ->setElementId($elementId)
                ->setElementKey($elementKey)
                ->setSelectionCode($selectionCode)
                ->setProductId($productId);

            $topProduct->setPosition($topProduct->getNextPosition());

            $topProduct->save();
        } catch(\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }

        return new JsonResponse(['id' => $topProduct->getId(), 'position' => $topProduct->getPosition()]);
    }

    public function removeProductAction($topProductId)
    {
        try {
            $topProduct = TopProductQuery::create()
                ->findOneById($topProductId);

            $topProduct->delete();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }

        return new JsonResponse();
    }

    public function updateProductAction($topProductId)
    {
        try {
            $newProductId = $this->getRequest()->get('newProductId');

            $topProduct = TopProductQuery::create()
                ->findOneById($topProductId);

            $topProduct->setProductId($newProductId)
                ->save();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }

        return new JsonResponse(['product' => $topProduct]);
    }

    public function updatePositionAction($topProductId)
    {
        try {

            $newPosition = $this->getRequest()->get('newPosition');

            $newPosition++;

            $topProduct = TopProductQuery::create()
                ->findOneById($topProductId);

            $topProduct->changeAbsolutePosition($newPosition);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }

        return new JsonResponse();
    }

}