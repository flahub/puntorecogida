<?php
namespace Orangecat\Puntorecogida\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Orangecat\Puntorecogida\Helper\Data;
use Orangecat\Puntorecogida\Model\ResourceModel\Tienda\Collection as TiendasCollection;
use Orangecat\Puntorecogida\Model\Tienda;
use Magento\Framework\UrlInterface;

class Puntorecogida extends \Magento\Framework\View\Element\Template
{
    private $_tiendasCollection;

    protected $puntorecogidaTienda;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Data $helperData,
        TiendasCollection $tiendasCollection,
        Tienda $puntorecogidaTienda
    )
    {
        $this->_storeManager = $storeManager;
        $this->_helperData = $helperData;
        $this->_tiendasCollection = $tiendasCollection;
        $this->_urlBuilder = $context->getUrlBuilder();
        $this->puntorecogidaTienda = $puntorecogidaTienda;
        parent::__construct($context);
    }

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getModuleConfigValue($code)
    {
        return $this->_helperData->getModuleConfigValue($code);
    }

    public function getCmsBlock(){
        $blockid = $this->getModuleConfigValue('cmsblock');
        $cmsblock=  $this->getLayout()
            ->createBlock('Magento\Cms\Block\Block')
            ->setBlockId($blockid)
            ->toHtml();
        return $cmsblock;
    }

    public function getPuntosRecogida()
    {
        $this->_tiendasCollection->addFilter('is_active', 1);
        $this->_tiendasCollection->setOrder('posicion','asc');

        $tiendas = $this->_tiendasCollection->getData();
        return $tiendas;
    }

    public function getImageUrl($img) {
        return $this->_urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]) . 'tiendaimages/' . $img;
    }

    public function getMetodoEnvio($idmetodo)
    {
        if($idmetodo==0) {
            return '';
        }
        $metodos = $this->puntorecogidaTienda->getAvailableOptions();
        foreach($metodos as $k => $metodo){
            if($idmetodo == $k){
                return  $metodo->getText();
            }
        }
        return '';
    }
}
