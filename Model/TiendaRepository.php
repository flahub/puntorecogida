<?php
namespace Orangecat\Puntorecogida\Model;

use Orangecat\Puntorecogida\Api\TiendaRepositoryInterface;
use Orangecat\Puntorecogida\Api\Data;
use Orangecat\Puntorecogida\Model\ResourceModel\Tienda as ResourceTienda;
use Orangecat\Puntorecogida\Model\ResourceModel\Tienda\CollectionFactory as TiendaCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

class TiendaRepository implements TiendaRepositoryInterface
{
    protected $resource;

    protected $tiendaFactory;

    protected $tiendaCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataTiendaFactory;

    private $storeManager;

    private $collectionProcessor;

    public function __construct(
        ResourceTienda $resource,
        TiendaFactory $tiendaFactory,
        \Orangecat\Puntorecogida\Api\Data\TiendaInterfaceFactory $dataTiendaFactory,
        TiendaCollectionFactory $tiendaCollectionFactory,
        Data\TiendaSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->resource = $resource;
        $this->tiendaFactory = $tiendaFactory;
        $this->tiendaCollectionFactory = $tiendaCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataTiendaFactory = $dataTiendaFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
    }

    public function save(Data\TiendaInterface $tienda)
    {
        if (empty($tienda->getStoreId())) {
            $tienda->setStoreId($this->storeManager->getStore()->getId());
        }

        try {
            $this->resource->save($tienda);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $tienda;
    }

    public function getById($tiendaId)
    {
        $tienda = $this->tiendaFactory->create();
        $this->resource->load($tienda, $tiendaId);
        if (!$tienda->getId()) {
            throw new NoSuchEntityException(__('The pickup point with the "%1" ID doesn\'t exist.', $tiendaId));
        }
        return $tienda;
    }

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $collection = $this->tiendaCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    public function delete(Data\TiendaInterface $tienda)
    {
        try {
            $this->resource->delete($tienda);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    public function deleteById($tiendaId)
    {
        return $this->delete($this->getById($tiendaId));
    }

    private function getCollectionProcessor()
    {
        if (!$this->collectionProcessor) {
            $this->collectionProcessor = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'Orangecat\Puntorecogida\Model\Api\SearchCriteria\TiendaCollectionProcessor'
            );
        }
        return $this->collectionProcessor;
    }
}
