<?php

namespace TopProducts\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use TopProducts\Model\TopProduct;
use TopProducts\Model\TopProductQuery;

class TopProductsController extends BaseAdminController
{
    #[Route('/admin/top_products/get/{elementKey}/{elementId}', name: 'top_products_get', methods: ['GET'])]
    public function getProductAction($elementKey, $elementId)
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], ['TopProducts'], AccessManager::VIEW)) {
            return $response;
        }

        $request = $this->getRequest();
        $locale = $request->hasSession()
            ? $request->getSession()->getLang()->getLocale()
            : (\Thelia\Model\LangQuery::create()->findOneByByDefault(true)?->getLocale() ?? 'en_US');

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

    #[Route('/admin/top_products/add/{elementKey}/{elementId}/{selectionCode}', name: 'top_products_add', methods: ['POST'])]
    public function addProductAction($elementKey, $elementId, $selectionCode)
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], ['TopProducts'], AccessManager::UPDATE)) {
            return $response;
        }

        try {
            $request = $this->getRequest();
            $productId = $request->attributes->get('productId', $request->query->get('productId', $request->request->get('productId')));

            $topProduct = (new TopProduct())
                ->setElementId($elementId)
                ->setElementKey($elementKey)
                ->setSelectionCode($selectionCode)
                ->setProductId($productId);

            $topProduct->setPosition($topProduct->getNextPosition());

            $topProduct->save();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }

        return new JsonResponse(['id' => $topProduct->getId(), 'position' => $topProduct->getPosition()]);
    }

    #[Route('/admin/top_products/remove/{topProductId}', name: 'top_products_remove', methods: ['POST'])]
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

    #[Route('/admin/top_products/update/{topProductId}', name: 'top_products_update', methods: ['POST'])]
    public function updateProductAction($topProductId)
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], ['TopProducts'], AccessManager::UPDATE)) {
            return $response;
        }

        try {
            $request = $this->getRequest();
            $newProductId = $request->attributes->get('newProductId', $request->query->get('newProductId', $request->request->get('newProductId')));

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

    #[Route('/admin/top_products/position/{topProductId}', name: 'top_products_position', methods: ['POST'])]
    public function updatePositionAction($topProductId)
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], ['TopProducts'], AccessManager::UPDATE)) {
            return $response;
        }

        try {

            $request = $this->getRequest();
            $newPosition = $request->attributes->get('newPosition', $request->query->get('newPosition', $request->request->get('newPosition')));

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
