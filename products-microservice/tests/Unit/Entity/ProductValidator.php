<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductValidatorTest extends TestCase
{
    public function testFieldLengthLimits()
    {
        $product = new Product();

        // Test pour name avec une longueur valide
        $product->setName(str_repeat('a', 255));
        $this->assertTrue($this->hasValidLength($product->getName(), 255));

        // Test pour name avec une longueur invalide
        $this->expectException(\InvalidArgumentException::class);
        $product->setName(str_repeat('a', 256));
    }

    public function testNegativePriceInCents()
    {
        $product = new Product();

        // Test pour priceInCents avec une valeur négative
        $this->expectException(\InvalidArgumentException::class);
        $product->setPriceInCents(-100);
    }

    public function testNegativeStock()
    {
        $product = new Product();

        // Test pour stock avec une valeur négative
        $this->expectException(\InvalidArgumentException::class);
        $product->setStock(-10);
    }

    public function testInvalidTypeForPriceInCents()
    {
        $product = new Product();

        // Test pour priceInCents avec un type invalide
        $this->expectException(\TypeError::class);
        $product->setPriceInCents('invalid_type');
    }

    public function testInvalidTypeForStock()
    {
        $product = new Product();

        // Test pour stock avec un type invalide
        $this->expectException(\TypeError::class);
        $product->setStock('invalid_type');
    }

    // Méthode utilitaire pour vérifier la longueur des champs
    private function hasValidLength($field, $maxLength)
    {
        return strlen($field) <= $maxLength;
    }
}
