<?php

namespace App\Tests\Integration\Service;

use App\Entity\Product;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductServiceTest extends KernelTestCase
{
    private ProductService $productService;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->productService = $kernel->getContainer()->get(ProductService::class);
    }

    public function testValidateProduct()
    {
        $product = new Product();
        $product->setName(str_repeat('a', 256));
        $product->setPriceInCents(-100);
        $product->setStock(-10);

        $errors = $this->productService->validateProduct($product);

        $this->assertContains('The name cannot be longer than 255 characters.', $errors);
        $this->assertContains('The price cannot be negative.', $errors);
        $this->assertContains('The stock cannot be negative.', $errors);
    }
}
