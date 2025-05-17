<?php
namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Uuid;

class ProductCustomerFilterExtension implements QueryCollectionExtensionInterface
{
    public function __construct(private Security $security) {}

    public function applyToCollection(QueryBuilder $qb, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?Operation $operation = null, array $context = []): void
    {
        if (
            !$operation instanceof GetCollection || 
            $operation->getUriTemplate() !== '/my-products' || 
            $resourceClass !== \App\Entity\Product::class || 
            $this->security->isGranted('ROLE_ADMIN')
        ) {
            return;
        }

     
        $rootAlias = $qb->getRootAliases()[0];
        $qb->andWhere("$rootAlias.customerUuid = :current_user")
        ->setParameter(
            'current_user', 
            Uuid::fromString($this->security->getUser()->getUuid())->toBinary(), // Convert to binary
            'uuid' // Tell Doctrine this is a UUID type
        );
    }
}