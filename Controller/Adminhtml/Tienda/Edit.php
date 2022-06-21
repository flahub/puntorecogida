<?php
namespace Orangecat\Puntorecogida\Controller\Adminhtml\Tienda;

use Magento\Framework\App\Action\HttpGetActionInterface;

class Edit extends \Orangecat\Puntorecogida\Controller\Adminhtml\Tienda implements HttpGetActionInterface
{
    protected $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
    }

    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('tienda_id');
        $model = $this->_objectManager->create(\Orangecat\Puntorecogida\Model\Tienda::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This pickup point no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('puntorecogida_tienda', $model);

        // 5. Build edit form
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Pickup Point') : __('New Pickup Point'),
            $id ? __('Edit Pickup Point') : __('New Pickup Point')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Pickup Points'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New Pickup Point'));
        return $resultPage;
    }
}
