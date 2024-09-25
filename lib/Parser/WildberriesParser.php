<?php

namespace Krasikoff\WbParser\Parser;

use Krasikoff\WbParser\Client\WildberriesClient;
use Krasikoff\WbParser\DTO\ProductWildberriesDto;

class WildberriesParser
{
    private WildberriesClient $client;

    public function __construct()
    {
        $this->client = new WildberriesClient();
    }

    /**
     * @param string $searchText
     * @param int $page
     * @return ProductWildberriesDto[]
     */
    public function searchProducts(string $searchText, int $page = 1): array
    {
        $productsArray = $this->client->getProductsArrayBySearch($searchText, $page);
        return $this->objectsFromArray($productsArray);
    }

    /**
     * @param mixed[] $data
     * @return ProductWildberriesDto
     */
    private function objectFromArray(array $data): ProductWildberriesDto
    {
        return new ProductWildberriesDto(
            $data['article'],
            $data['name'],
            $data['brand'],
            $data['image'],
            $data['price'],
            $data['oldPrice'],
            $data['percentSale'],
            $data['link'],
        );
    }

    /**
     * @param mixed[] $data
     * @return ProductWildberriesDto[]
     */
    private function objectsFromArray(array $data): array
    {
        $products = [];
        foreach ($data as $item) {
            $products[] = $this->objectFromArray($item);
        }
        return $products;
    }
}