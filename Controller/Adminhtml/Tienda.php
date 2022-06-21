<?php
namespace Orangecat\Puntorecogida\Controller\Adminhtml;

abstract class Tienda extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Orangecat_Puntorecogida::tienda';

    protected $_coreRegistry;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry)
    {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    protected function initPage($resultPage)
    {
        $resultPage->setActiveMenu('Orangecat_Puntorecogida::puntorecogida_tienda')
            ->addBreadcrumb(__('Puntorecogida'), __('Puntorecogida'))
            ->addBreadcrumb(__('Pickup points'), __('Pickup points'));
        return $resultPage;
    }
}
