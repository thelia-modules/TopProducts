<?php

declare(strict_types=1);

/*
 * This file is part of the Thelia package.
 * http://www.thelia.net
 *
 * (c) OpenStudio <info@thelia.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TopProducts\Api\Extension;

use ApiPlatform\Metadata\Operation;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Thelia\Api\Bridge\Propel\Extension\QueryCollectionExtensionInterface;
use TopProducts\Api\Resource\TopProduct;
use TopProducts\Model\Map\TopProductTableMap;

/**
 * Sorts the top products by position when the caller did not choose an order, as
 * the `top_products` loop does. The bridge applies no default order of its own.
 */
final class TopProductPositionExtension implements QueryCollectionExtensionInterface
{
    public function applyToCollection(ModelCriteria $query, string $resourceClass, ?Operation $operation = null, array $context = []): void
    {
        if (TopProduct::class !== $resourceClass) {
            return;
        }

        if (isset($context['filters']['order']) && \is_array($context['filters']['order'])) {
            return;
        }

        $query
            ->orderBy(TopProductTableMap::COL_POSITION, Criteria::ASC)
            ->orderBy(TopProductTableMap::COL_ID, Criteria::ASC);
    }
}
