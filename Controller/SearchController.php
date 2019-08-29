<?php


namespace TopProducts\Controller;

use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Model\Category;
use Thelia\Model\CategoryQuery;
use Thelia\Model\Map\ProductI18nTableMap;
use Thelia\Model\Map\ProductTableMap;
use Thelia\Model\Product;
use Thelia\Model\ProductI18n;
use Thelia\Model\ProductQuery;

class SearchController extends BaseAdminController
{
    public function searchAction($elementKey, $elementId)
    {
        $search = $this->getRequest()->get('q');

        $locale = $this->getSession()->getLang()->getLocale();

        $productQuery= ProductQuery::create()
            ->joinWithI18n($locale);

        switch ($elementKey) {
            case 'category' :
                $this->addCategoryFilter($productQuery, $elementId);
                break;
            case 'brand' :
                $this->addBrandFilter($productQuery, $elementId);
                break;
        }

        $searchArray = explode(" ", $search);

        $searchConditions = [];
        $referenceConditions = [];
        $i = 0;
        foreach ($searchArray as $searchTerm) {
            $i++;
            $searchConditionName = "search_$i";
            $referenceConditionName = "ref_$i";
            $productQuery->addCond($searchConditionName, ProductI18nTableMap::TITLE, "%$searchTerm%", Criteria::LIKE);
            $searchConditions[] = $searchConditionName;

            $productQuery->addCond($referenceConditionName, ProductTableMap::REF, "$searchTerm", Criteria::LIKE);
            $referenceConditions[] = $referenceConditionName;
        }

        $productQuery->combine($searchConditions, Criteria::LOGICAL_AND, 'search');
        $productQuery->combine($referenceConditions, Criteria::LOGICAL_AND, 'ref');

        $productQuery->combine(['search', 'ref'], Criteria::LOGICAL_OR);

//        dump($productQuery->toString());

        $productQuery
//            ->useProductI18nQuery()
//                ->filterByLocale($locale)
//                ->filterByTitle("%$search%", Criteria::LIKE)
//            ->endUse()
            ->groupById();

        $productResults = $productQuery->find();

        $products = [];

        /** @var Product $product */
        foreach ($productResults as $product) {
            $products[] = [
                'id' => $product->getId(),
                'title' => $product->getTitle(),
                'reference' => $product->getRef(),
                'visible' => $product->getVisible()
            ];
        }

        return new JsonResponse(compact('products'));
    }

    /**
     * @param ProductQuery $query
     * @param int $categoryId
     */
    protected function addCategoryFilter(&$query, $categoryId)
    {
        $children = [$categoryId];

        $this->getAllChildren($categoryId, $children);

        $query->useProductCategoryQuery()
            ->filterByCategoryId($children)
        ->endUse();
    }

    /**
     * @param ProductQuery $query
     * @param int $brandId
     */
    protected function addBrandFilter(&$query, $brandId)
    {
        $query->useBrandQuery()
            ->filterById($brandId)
        ->endUse();
    }

    protected function getAllChildren($categoryId, &$children)
    {
        $childResults = CategoryQuery::create()
            ->filterByParent($categoryId)
            ->select(['id'])
            ->find()
            ->toArray();

        $children = array_merge_recursive($children, $childResults);

        foreach ($childResults as $childResult) {
            $this->getAllChildren($childResult, $children);
        }
    }
}