<?php
namespace Orangecat\Puntorecogida\Model\ResourceModel\Tienda\Relation\Store;

use Orangecat\Puntorecogida\Model\ResourceModel\Tienda;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

class ReadHandler implements ExtensionInterface
{
    protected $resourceTienda;

    public function __construct(
        Tienda $resourceTienda
    ) {
        $this->resourceTienda = $resourceTienda;
    }

    public function execute($entity, $arguments = [])
    {
        if ($entity->getId()) {
            $stores = $this->resourceTienda->lookupStoreIds((int)$entity->getId());
            $entity->setData('store_id', $stores);
            $entity->setData('stores', $stores);
        }
        return $entity;
    }
}
