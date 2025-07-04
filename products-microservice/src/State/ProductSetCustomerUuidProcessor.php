<?php
namespace App\State;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Product;
use Symfony\Bundle\SecurityBundle\Security;

class ProductSetCustomerUuidProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $persistProcessor,
        private Security $security
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        //Permet d'ajouter automatiquement le Uuid sans que l'utilisateur ne puisse le faire et donc potentiellement créer une commande pour quelqu'un d'autre.
        if (
            $data instanceof Product && 
            $operation instanceof Post
        ) {
            $user = $this->security->getUser();
            if ($user && method_exists($user, 'getUuid')) {
                $data->setCustomerUuid($user->getUuid());
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}