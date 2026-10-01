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

namespace TopProducts\Api\Resource;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Propel\Runtime\Map\TableMap;
use Symfony\Component\Serializer\Attribute\Groups;
use Thelia\Api\Bridge\Propel\Attribute\Relation;
use Thelia\Api\Bridge\Propel\Filter\OrderFilter;
use Thelia\Api\Bridge\Propel\Filter\SearchFilter;
use Thelia\Api\Resource\Product;
use Thelia\Api\Resource\PropelResourceInterface;
use Thelia\Api\Resource\PropelResourceTrait;
use TopProducts\Model\Map\TopProductTableMap;

/**
 * Read-only view of the products pinned to a category or a brand.
 *
 * The collection is not paginated: a selection holds a handful of products and a
 * truncated list would silently drop pinned products. Rows come back by ascending
 * position unless the caller asks for another `order[...]`.
 */
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/front/top_products',
            paginationEnabled: false,
        ),
        new Get(uriTemplate: '/front/top_products/{id}'),
    ],
    normalizationContext: ['groups' => [self::GROUP_FRONT_READ]],
)]
#[ApiFilter(
    filterClass: SearchFilter::class,
    properties: [
        'elementKey',
        'elementId',
        'selectionCode',
    ],
)]
#[ApiFilter(
    filterClass: OrderFilter::class,
    properties: [
        'position',
        'id',
    ],
)]
class TopProduct implements PropelResourceInterface
{
    use PropelResourceTrait;

    public const GROUP_FRONT_READ = 'front:top_product:read';

    #[Groups([self::GROUP_FRONT_READ])]
    public ?int $id = null;

    #[Groups([self::GROUP_FRONT_READ])]
    public ?string $elementKey = null;

    #[Groups([self::GROUP_FRONT_READ])]
    public ?int $elementId = null;

    #[Groups([self::GROUP_FRONT_READ])]
    public ?string $selectionCode = null;

    #[Groups([self::GROUP_FRONT_READ])]
    public ?int $position = null;

    #[Relation(targetResource: Product::class)]
    #[Groups([self::GROUP_FRONT_READ])]
    public ?Product $product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getElementKey(): ?string
    {
        return $this->elementKey;
    }

    public function setElementKey(?string $elementKey): self
    {
        $this->elementKey = $elementKey;

        return $this;
    }

    public function getElementId(): ?int
    {
        return $this->elementId;
    }

    public function setElementId(?int $elementId): self
    {
        $this->elementId = $elementId;

        return $this;
    }

    public function getSelectionCode(): ?string
    {
        return $this->selectionCode;
    }

    public function setSelectionCode(?string $selectionCode): self
    {
        $this->selectionCode = $selectionCode;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public static function getPropelRelatedTableMap(): ?TableMap
    {
        return new TopProductTableMap();
    }
}
