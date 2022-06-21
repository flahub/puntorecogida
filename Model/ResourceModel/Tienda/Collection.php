<?php
namespace Orangecat\Puntorecogida\Model\ResourceModel\Tienda;

use Orangecat\Puntorecogida\Api\Data\TiendaInterface;
use \Orangecat\Puntorecogida\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'tienda_id';

    protected $_eventPrefix = 'puntorecogida_tienda_collection';

    protected $_eventObject = 'tienda_collection';

    protected function _afterLoad()
    {
        $entityMetadata = $this->metadataPool->getMetadata(TiendaInterface::class);

        $this->performAfterLoad('puntorecogida_tienda_store', $entityMetadata->getLinkField());

        return parent::_afterLoad();
    }

    protected function _construct()
    {
        $this->_init(\Orangecat\Puntorecogida\Model\Tienda::class, \Orangecat\Puntorecogida\Model\ResourceModel\Tienda::class);
        $this->_map['fields']['store'] = 'store_table.store_id';
        $this->_map['fields']['tienda_id'] = 'main_table.tienda_id';
    }

    public function toOptionArray()
    {
        return $this->_toOptionArray('tienda_id', 'nombre');
    }

    public function addStoreFilter($store, $withAdmin = true)
    {
        $this->performAddStoreFilter($store, $withAdmin);

        return $this;
    }

    protected function _renderFiltersBefore()
    {
        $entityMetadata = $this->metadataPool->getMetadata(TiendaInterface::class);
        $this->joinStoreRelationTable('puntorecogida_tienda_store', $entityMetadata->getLinkField());
    }
}
