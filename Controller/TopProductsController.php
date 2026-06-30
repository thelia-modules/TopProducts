<?php


namespace TopProducts\Controller;


use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use TopProducts\Model\TopProduct;
use TopProducts\Model\TopProductQuery;

class TopProductsController extends BaseAdminController
{
    public function getProductAction($elementKey, $elementId)
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], ['TopProducts'], AccessManager::VIEW)) {
            return $response;
        }

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
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], ['TopProducts'], AccessManager::UPDATE)) {
            return $response;
        }

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
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], ['TopProducts'], AccessManager::UPDATE)) {
            return $response;
        }

        try {
            $topProduct = TopProductQuery::create()
                ->findOneById($topProductId);

            if (null === $topProduct) {
                return new JsonResponse(['error' => 'Top product not found'], 404);
            }

            $topProduct->delete();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }

        return new JsonResponse();
    }

    public function updateProductAction($topProductId)
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], ['TopProducts'], AccessManager::UPDATE)) {
            return $response;
        }

        try {
            $newProductId = $this->getRequest()->get('newProductId');

            $topProduct = TopProductQuery::create()
                ->findOneById($topProductId);

            if (null === $topProduct) {
                return new JsonResponse(['error' => 'Top product not found'], 404);
            }

            $topProduct->setProductId($newProductId)
                ->save();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }

        return new JsonResponse(['product' => $topProduct]);
    }

    public function updatePositionAction($topProductId)
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], ['TopProducts'], AccessManager::UPDATE)) {
            return $response;
        }

        try {

            $newPosition = $this->getRequest()->get('newPosition');

            $newPosition++;

            $topProduct = TopProductQuery::create()
                ->findOneById($topProductId);

            if (null === $topProduct) {
                return new JsonResponse(['error' => 'Top product not found'], 404);
            }

            $topProduct->changeAbsolutePosition($newPosition);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }

        return new JsonResponse();
    }

}