<?php
namespace Orangecat\Puntorecogida\Controller\Adminhtml\Tienda;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Orangecat\Puntorecogida\Api\TiendaRepositoryInterface;
use Orangecat\Puntorecogida\Model\Tienda;
use Orangecat\Puntorecogida\Model\TiendaFactory;

class Save extends \Orangecat\Puntorecogida\Controller\Adminhtml\Tienda implements HttpPostActionInterface
{
    protected $dataPersistor;

    private $tiendaFactory;

    private $tiendaRepository;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        DataPersistorInterface $dataPersistor,
        TiendaFactory $tiendaFactory = null,
        TiendaRepositoryInterface $tiendaRepository = null
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->tiendaFactory = $tiendaFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(TiendaFactory::class);
        $this->tiendaRepository = $tiendaRepository
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(TiendaRepositoryInterface::class);
        parent::__construct($context, $coreRegistry);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Tienda::STATUS_ENABLED;
            }
            if (empty($data['tienda_id'])) {
                $data['tienda_id'] = null;
            }

            $model = $this->tiendaFactory->create();

            $id = $this->getRequest()->getParam('tienda_id');
            if ($id) {
                try {
                    $model = $this->tiendaRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This pickup point no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            if (isset($data['image'][0]['name']) && isset($data['image'][0]['tmp_name'])) {
                $data['image'] = $data['image'][0]['name'];
            } elseif (isset($data['image'][0]['name']) && !isset($data['image'][0]['tmp_name'])) {
                $data['image'] =$data['image'][0]['name'];
            } else {
                $data['image'] = null;
            }

            $model->setData($data);

            try {
                $this->tiendaRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You have saved the pickup point.'));
                $this->dataPersistor->clear('puntorecogida_tienda');
                return $this->processTiendaReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong with saving the pickup point.'));
            }

            $this->dataPersistor->set('puntorecogida_tienda', $data);
            return $resultRedirect->setPath('*/*/edit', ['tienda_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    private function processTiendaReturn($model, $data, $resultRedirect)
    {
        $redirect = $data['back'] ?? 'close';

        if ($redirect ==='continue') {
            $resultRedirect->setPath('*/*/edit', ['tienda_id' => $model->getId()]);
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        } elseif ($redirect === 'duplicate') {
            $duplicateModel = $this->tiendaFactory->create(['data' => $data]);
            $duplicateModel->setId(null);
            $duplicateModel->setIsActive(Tienda::STATUS_DISABLED);
            $this->tiendaRepository->save($duplicateModel);
            $id = $duplicateModel->getId();
            $this->messageManager->addSuccessMessage(__('You duplicated the pickup point.'));
            $this->dataPersistor->set('puntorecogida_tienda', $data);
            $resultRedirect->setPath('*/*/edit', ['tienda_id' => $id]);
        }
        return $resultRedirect;
    }
}
