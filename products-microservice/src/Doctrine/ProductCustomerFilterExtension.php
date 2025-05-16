<?php
namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

class ProductCustomerFilterExtension implements QueryCollectionExtensionInterface
{
    public function __construct(private Security $security) {}

    public function applyToCollection(QueryBuilder $qb, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        if ($resourceClass !== \App\Entity\Product::class || $this->security->isGranted('ROLE_ADMIN')) {
            return;
        }

        dump($this->security->getUser());die;

        $rootAlias = $qb->getRootAliases()[0];
        $qb->andWhere("$rootAlias.customerId = :current_user")
           ->setParameter('current_user', $this->security->getUser()->getId());
    }
}