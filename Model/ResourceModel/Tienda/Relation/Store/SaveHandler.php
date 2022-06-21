<?php
namespace Orangecat\Puntorecogida\Model\ResourceModel\Tienda\Relation\Store;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Orangecat\Puntorecogida\Api\Data\TiendaInterface;
use Orangecat\Puntorecogida\Model\ResourceModel\Tienda;
use Magento\Framework\EntityManager\MetadataPool;

class SaveHandler implements ExtensionInterface
{
    protected $metadataPool;

    protected $resourceTienda;

    public function __construct(
        MetadataPool $metadataPool,
        Tienda $resourceTienda
    ) {
        $this->metadataPool = $metadataPool;
        $this->resourceTienda = $resourceTienda;
    }

    public function execute($entity, $arguments = [])
    {
        $entityMetadata = $this->metadataPool->getMetadata(TiendaInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $connection = $entityMetadata->getEntityConnection();

        $oldStores = $this->resourceTienda->lookupStoreIds((int)$entity->getId());
        $newStores = (array)$entity->getStores();

        $table = $this->resourceTienda->getTable('puntorecogida_tienda_store');

        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = [
                $linkField . ' = ?' => (int)$entity->getData($linkField),
                'store_id IN (?)' => $delete,
            ];
            $connection->delete($table, $where);
        }

        $insert = array_diff($newStores, $oldStores);
        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    $linkField => (int)$entity->getData($linkField),
                    'store_id' => (int)$storeId,
                ];
            }
            $connection->insertMultiple($table, $data);
        }

        return $entity;
    }
}
