<?php
declare(strict_types=1);

namespace Anee\Shopfinder\GraphQl\Data;
use Anee\Shopfinder\Api\Data\ShopfinderInterface;

class UpdateShopfinderShopData
{
    public function __construct(
        private readonly array $data
    ) {
    }

    public function getEntityId(): int
    {
        return $this->data[ShopfinderInterface::ENTITY_ID];
    }

    public function getName(): string
    {
        return $this->data[ShopfinderInterface::NAME];
    }

    public function getIdentifier(): string
    {
        return $this->data[ShopfinderInterface::IDENTIFIER];
    }

    public function getCountry(): string
    {
        return $this->data[ShopfinderInterface::COUNTRY];
    }

    public function getImage(): ?string
    {
        return $this->data[ShopfinderInterface::IMAGE] ?? null;
    }

    public function getLongitude(): ?string
    {
        return $this->data[ShopfinderInterface::LONGITUDE] ?? null;
    }

    public function getLatitude(): ?string
    {
        return $this->data[ShopfinderInterface::LATITUDE] ?? null;
    }
}
