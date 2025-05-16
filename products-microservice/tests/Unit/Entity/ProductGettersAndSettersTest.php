<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductGettersAndSettersTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $product = new Product();

        // Test pour l'ID - devrait être null initialement
        $this->assertNull($product->getId());

        // Test pour createdAt
        $createdAt = new \DateTimeImmutable();
        $product->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $product->getCreatedAt());

        // Test pour name
        $product->setName('Test Product');
        $this->assertEquals('Test Product', $product->getName());

        // Test pour priceInCents
        $product->setPriceInCents(1000);
        $this->assertEquals(1000, $product->getPriceInCents());

        // Test pour description
        $product->setDescription('This is a test product');
        $this->assertEquals('This is a test product', $product->getDescription());

        // Test pour color
        $product->setColor('Red');
        $this->assertEquals('Red', $product->getColor());

        // Test pour stock
        $product->setStock(10);
        $this->assertEquals(10, $product->getStock());
    }

    public function testDefaultValues()
    {
        $product = new Product();

        // Test pour les valeurs par défaut
        $this->assertNull($product->getId());
        $this->assertNull($product->getCreatedAt());
        $this->assertNull($product->getName());
        $this->assertNull($product->getPriceInCents());
        $this->assertNull($product->getDescription());
        $this->assertNull($product->getColor());
        $this->assertNull($product->getStock());
    }
}
