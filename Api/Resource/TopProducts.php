<?php

namespace TopProducts\Api\Resource;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Propel\Runtime\Map\TableMap;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Thelia\Api\Bridge\Propel\Filter\SearchFilter;
use Thelia\Api\Bridge\Propel\State\PropelCollectionProvider;
use Thelia\Api\Resource\PropelResourceInterface;
use Thelia\Api\Resource\PropelResourceTrait;
use Thelia\Model\Map\ProductTableMap;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/front/top-products',
            paginationEnabled: true,
            provider: PropelCollectionProvider::class,
        ),
    ],
    normalizationContext: ['groups' => [self::GROUP_FRONT_READ]]
)]
#[ApiFilter(
    filterClass: SearchFilter::class,
    properties: [
        'id',
        'elementKey',
        'productId',
        'selectionCode',
    ]
)]
class TopProducts implements PropelResourceInterface
{
    use PropelResourceTrait;

    public const GROUP_FRONT_READ = 'front:top_products:read';

    #[Groups([self::GROUP_FRONT_READ])]
    public ?int $id = null;

    #[Groups([self::GROUP_FRONT_READ])]
    public ?string $elementKey = null;

    #[Groups([ self::GROUP_FRONT_READ])]
    public ?int $productId = null;

    #[Groups([self::GROUP_FRONT_READ])]
    public ?string $selectionCode = null;

    #[Groups([self::GROUP_FRONT_READ])]
    public ?int $position = null;

    #[Ignore]
    public static function getPropelRelatedTableMap(): ?TableMap
    {
        return new ProductTableMap();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getElementKey()
    {
        return $this->elementKey;
    }

    public function setElementKey(string $elementKey): self
    {
        $this->elementKey = $elementKey;

        return $this;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function setProductId(int $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function getSelectionCode(): string
    {
        return $this->selectionCode;
    }

    public function setSelectionCode(string $selectionCode): self
    {
        $this->selectionCode = $selectionCode;

        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
