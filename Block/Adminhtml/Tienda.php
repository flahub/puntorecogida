<?php
namespace Orangecat\Puntorecogida\Block\Adminhtml;

class Tienda extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'Orangecat_Puntorecogida';
        $this->_controller = 'adminhtml_tienda';
        $this->_headerText = __('Pickup point');
        $this->_addButtonLabel = __('Add New Pickup Point');
        parent::_construct();
    }
}
