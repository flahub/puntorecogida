<?php
namespace Orangecat\Puntorecogida\Model\Tienda;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Orangecat\Puntorecogida\Model\ResourceModel\Tienda\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{
    protected $collection;

    protected $dataPersistor;

    protected $loadedData;

    public $_storeManager;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $tiendaCollectionFactory,
        DataPersistorInterface $dataPersistor,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->collection = $tiendaCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        $media_url =  $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'tiendaimages/';
        foreach ($items as $tienda) {
            $this->loadedData[$tienda->getId()] = $tienda->getData();
            if ($tienda->getImage()) {
                $m['image'][0]['name'] = $tienda->getImage();
                $m['image'][0]['url'] = $media_url . $tienda->getImage();
                $fullData = $this->loadedData;
                $this->loadedData[$tienda->getId()] = array_merge($fullData[$tienda->getId()], $m);
            }
        }

        $data = $this->dataPersistor->get('puntorecogida_tienda');
        if (!empty($data)) {
            $tienda = $this->collection->getNewEmptyItem();
            $tienda->setData($data);
            $this->loadedData[$tienda->getId()] = $tienda->getData();
            $this->dataPersistor->clear('puntorecogida_tienda');
        }

        return $this->loadedData;
    }
}
