<?php
namespace Orangecat\Puntorecogida\Controller\Adminhtml\Tienda;

use Magento\Framework\App\Action\HttpPostActionInterface;

class Delete extends \Orangecat\Puntorecogida\Controller\Adminhtml\Tienda implements HttpPostActionInterface
{
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('tienda_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Orangecat\Puntorecogida\Model\Tienda::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('The pickup point has been deleted.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['tienda_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('It cannot find a pickup point to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
