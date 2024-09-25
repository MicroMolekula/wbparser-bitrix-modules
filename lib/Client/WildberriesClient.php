<?php

namespace Krasikoff\WbParser\Client;

use Facebook\WebDriver\Chrome\ChromeDevToolsDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;

class WildberriesClient
{
    private const USER_AGENTS = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:124.0) Gecko/20100101 Firefox/124.0',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36 Edg/91.0.864.59',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
    ];

    public RemoteWebDriver $driver;

    public function __construct()
    {
        $config = \Bitrix\Main\Config\Configuration::getInstance('krasikoff.wbparser');
        $host = $config->get('selenium_uri');
        $capabilities = DesiredCapabilities::chrome();
        $chromeOptions = new ChromeOptions();
        $chromeOptions->addArguments(['--headless']);

        $capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);

        $this->driver = RemoteWebDriver::create($host, $capabilities);

        $devTools = new ChromeDevToolsDriver($this->driver);
        $devTools->execute(
            'Network.setUserAgentOverride',
            ['userAgent' => self::USER_AGENTS[random_int(0, count(self::USER_AGENTS) - 1)]],
        );
    }

    public function getProductsArrayBySearch(string $text, int $page = 1): array
    {
        $this->driver->get('https://www.wildberries.ru/catalog/0/search.aspx?search=' . urlencode($text) . '&page=' . $page);
        $this->waitLoadAllProducts();
        $productsHtml = $this->driver->findElements(WebDriverBy::cssSelector('.product-card__wrapper'));
        $productsArray = [];

        foreach ($productsHtml as $productHtml) {
            $productsArray[] = [
                'brand' => $this->getProductBrand($productHtml),
                'name' => $this->getProductName($productHtml),
                'article' => $this->getProductArticle($productHtml),
                'image' => $this->getProductsImage($productHtml),
                'price' => $this->getProductPrice($productHtml),
                'oldPrice' => $this->getPriceWithoutSale($productHtml),
                'percentSale' => $this->getPercentSale($productHtml),
                'link' => $this->getLinkProduct($productHtml),
            ];
        }

        return $productsArray;
    }

    private function waitLoadAllProducts(): void
    {
        while (true) {
            try {
                $productsHtml = $this->driver->findElements(WebDriverBy::cssSelector('.product-card__wrapper'));
                if (count($productsHtml) === 100) {
                    break;
                } else {
                    $this->driver->getKeyboard()->sendKeys(WebDriverKeys::PAGE_DOWN);
                }
            } catch (\Exception $e) {
                $this->driver->wait(2);
            }
        }
    }

    private function getProductName(RemoteWebElement $htmlElement): string
    {
        $product = $htmlElement
            ->findElement(WebDriverBy::cssSelector('.product-card__name'))
            ->getText();
        return str_replace('/ ', '', $product);
    }

    private function getProductBrand(RemoteWebElement $htmlElement): string
    {
        return $htmlElement
            ->findElement(WebDriverBy::cssSelector('.product-card__brand'))
            ->getText();
    }

    private function getProductArticle(RemoteWebElement $htmlElement): string
    {
        $link = $htmlElement
            ->findElement(WebDriverBy::cssSelector('.product-card__link'))
            ->getAttribute('href');
        return explode('/',$link)[4];
    }

    private function getProductsImage(RemoteWebElement $htmlElement): string
    {
        return $htmlElement
            ->findElement(WebDriverBy::cssSelector('.product-card__img-wrap'))
            ->findElement(WebDriverBy::tagName('img'))
            ->getAttribute('src');
    }

    private function getProductPrice(RemoteWebElement $htmlElement): int
    {
        $price = $htmlElement
            ->findElement(WebDriverBy::cssSelector('.product-card__price'))
            ->findElement(WebDriverBy::cssSelector('.price__lower-price'))
            ->getText();
        return (int)implode(explode(' ', $price));
    }

    private function getPriceWithoutSale(RemoteWebElement $htmlElement): int
    {
        try {
            $oldPrice = $htmlElement
                ->findElement(WebDriverBy::cssSelector('.product-card__price'))
                ->findElement(WebDriverBy::cssSelector('.price__wrap'))
                ->findElement(WebDriverBy::tagName('del'))
                ->getText();
            return (int)implode(explode(' ', $oldPrice));
        } catch (NoSuchElementException $e) {
            return $this->getProductPrice($htmlElement);
        }
    }

    private function getPercentSale(RemoteWebElement $htmlElement): string
    {
        try {
            return $htmlElement
                ->findElement(WebDriverBy::cssSelector('.product-card__tip--sale'))
                ->getText();
        } catch (NoSuchElementException $e) {
            return "-0%";
        }
    }

    private function getLinkProduct(RemoteWebElement $htmlElement): string
    {
        return $htmlElement
            ->findElement(WebDriverBy::cssSelector('.product-card__link'))
            ->getAttribute('href');
    }

    public function __destruct()
    {
        $this->driver->quit();
    }
}