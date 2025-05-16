<?php

namespace App\Service;

use App\Entity\Product;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductService
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validateProduct(Product $product): array
    {
        $errors = $this->validator->validate($product);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $errorMessages;
        }

        return [];
    }
}
