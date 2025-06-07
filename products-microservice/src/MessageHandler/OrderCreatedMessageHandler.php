<?php
namespace App\MessageHandler;

use App\Exception\QuantityException;
use App\Message\OrderCancelledMessage;
use App\Message\OrderCreatedMessage;
use App\Message\OrderValidatedMessage;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class OrderCreatedMessageHandler
{
    public function __construct(
        private ProductRepository $productRepository,
        private EntityManagerInterface $em,
        private LoggerInterface $logger,
        private MessageBusInterface $bus,
    ) {}

    private function logError(string $errorMsg, OrderCreatedMessage $message, ?string $exceptionMessage = null){
        $context = [
            'error' => $errorMsg,
            'content' => [
                'orderId' => $message->orderId,
                'customerUuid' => $message->customerUuid,
                'products' => $message->products,
            ]
        ];
        if ($exceptionMessage) {
            $context['exception_message'] = $exceptionMessage;
        }
        $this->logger->error($errorMsg, $context);
    }


    public function __invoke(OrderCreatedMessage $message)
    {
        try{
            foreach ($message->products as $productData) {
                $product = $this->productRepository->find($productData['productId']);

                if ($product) {
                    $product->decreaseStock($productData['quantity']);
    
                } else {
                    $this->logError(
                        'Product not found: ID ' . $productData['productId'], 
                        $message
                    );
                    
                    //Cancel order
                    $this->logger->error('Dispatching OrderCancelledMessage');
                    $this->bus->dispatch(new OrderCancelledMessage($message->orderId), [new AmqpStamp('order.cancelled')]);
                    return;
                }
            }

            $this->em->flush();

            $this->logger->error('Dispatching OrderValidatedMessage: '.$message->orderId);
            $this->bus->dispatch(new OrderValidatedMessage($message->orderId), [new AmqpStamp('order.validated')]);
        }
        catch(QuantityException $e){
            $this->logError('Insufficient stock', $message, $e->getMessage());

            //Cancel order
            $this->logger->error('Dispatching OrderCancelledMessage');
            $this->bus->dispatch(new OrderCancelledMessage($message->orderId), [new AmqpStamp('order.cancelled')]);
            
        }

    }
}
