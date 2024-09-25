<?php

namespace Krasikoff\WbParser\DTO;

class ProductWildberriesDto
{
    public function __construct(
        private string $article,
        private string $name,
        private string $brand,
        private string $image,
        private int $price,
        private int $oldPrice,
        private string $percentSale,
        private string $link,
    ){
    }

    public function getArticle(): string
    {
        return $this->article;
    }

    public function setArticle(string $article): self
    {
        $this->article = $article;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;
        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getOldPrice(): int
    {
        return $this->oldPrice;
    }

    public function setOldPrice(int $oldPrice): self
    {
        $this->oldPrice = $oldPrice;
        return $this;
    }

    public function getPercentSale(): string
    {
        return $this->percentSale;
    }

    public function setPercentSale(string $percentSale): self
    {
        $this->percentSale = $percentSale;
        return $this;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;
        return $this;
    }
}