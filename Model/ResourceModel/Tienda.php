<?php
namespace Orangecat\Puntorecogida\Model\ResourceModel;

use Orangecat\Puntorecogida\Api\Data\TiendaInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

class Tienda extends AbstractDb
{
    protected $_storeManager;

    protected $entityManager;

    protected $metadataPool;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        EntityManager $entityManager,
        MetadataPool $metadataPool,
        $connectionName = null
    ) {
        $this->_storeManager = $storeManager;
        $this->entityManager = $entityManager;
        $this->metadataPool = $metadataPool;
        parent::__construct($context, $connectionName);
    }

    protected function _construct()
    {
        $this->_init('puntorecogida_tienda', 'tienda_id');
    }

    public function getConnection()
    {
        return $this->metadataPool->getMetadata(TiendaInterface::class)->getEntityConnection();
    }

    protected function _beforeSave(AbstractModel $object)
    {
        return $this;
    }

    private function getTiendaId(AbstractModel $object, $value, $field = null)
    {
        $entityMetadata = $this->metadataPool->getMetadata(TiendaInterface::class);
        if (!$field) {
            $field = $entityMetadata->getIdentifierField();
        }
        $entityId = $value;
        if ($field != $entityMetadata->getIdentifierField() || $object->getStoreId()) {
            $select = $this->_getLoadSelect($field, $value, $object);
            $select->reset(Select::COLUMNS)
                ->columns($this->getMainTable() . '.' . $entityMetadata->getIdentifierField())
                ->limit(1);
            $result = $this->getConnection()->fetchCol($select);
            $entityId = count($result) ? $result[0] : false;
        }
        return $entityId;
    }

    public function load(AbstractModel $object, $value, $field = null)
    {
        $tiendaId = $this->getTiendaId($object, $value, $field);
        if ($tiendaId) {
            $this->entityManager->load($object, $tiendaId);
        }
        return $this;
    }

    protected function _getLoadSelect($field, $value, $object)
    {
        $entityMetadata = $this->metadataPool->getMetadata(TiendaInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $stores = [(int)$object->getStoreId(), Store::DEFAULT_STORE_ID];

            $select->join(
                ['css' => $this->getTable('puntorecogida_tienda_store')],
                $this->getMainTable() . '.' . $linkField . ' = css.' . $linkField,
                ['store_id']
            )
                ->where('is_active = ?', 1)
                ->where('css.store_id in (?)', $stores)
                ->order('store_id DESC')
                ->limit(1);
        }

        return $select;
    }

    public function lookupStoreIds($id)
    {
        $connection = $this->getConnection();

        $entityMetadata = $this->metadataPool->getMetadata(TiendaInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
            ->from(['css' => $this->getTable('puntorecogida_tienda_store')], 'store_id')
            ->join(
                ['cs' => $this->getMainTable()],
                'css.' . $linkField . ' = cs.' . $linkField,
                []
            )
            ->where('cs.' . $entityMetadata->getIdentifierField() . ' = :tienda_id');

        return $connection->fetchCol($select, ['tienda_id' => (int)$id]);
    }

    public function save(AbstractModel $object)
    {
        $this->entityManager->save($object);
        return $this;
    }

    public function delete(AbstractModel $object)
    {
        $this->entityManager->delete($object);
        return $this;
    }
}
