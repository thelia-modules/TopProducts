<?php

declare(strict_types=1);

namespace TopProducts\Twig;

use Thelia\Api\Service\DataAccess\DataAccessService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class TopProductsExtension extends AbstractExtension
{
    public function __construct(private readonly DataAccessService $dataAccessService)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getTopProducts', [$this, 'getTopProducts']),
        ];
    }

    public function getTopProducts(?array $params = []): array|null|object
    {
        return $this->dataAccessService->resources('/api/front/top-products', $params);
    }
}
