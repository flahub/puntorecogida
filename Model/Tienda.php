<?php
namespace Orangecat\Puntorecogida\Model;

use Orangecat\Puntorecogida\Api\Data\TiendaInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Tienda extends AbstractModel implements TiendaInterface, IdentityInterface
{
    const CACHE_TAG = 'puntorecogida_s';

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    const OPTION_0 = 0;
    const OPTION_A = 1;
    const OPTION_B = 2;
    const OPTION_C = 3;

    protected $_cacheTag = self::CACHE_TAG;

    protected $_eventPrefix = 'puntorecogida_tienda';

    protected function _construct()
    {
        $this->_init(\Orangecat\Puntorecogida\Model\ResourceModel\Tienda::class);
    }

    public function beforeSave()
    {
        if ($this->hasDataChanges()) {
            $this->setUpdateTime(null);
        }

        $needle = 'tienda_id="' . $this->getId() . '"';
        if (false == strstr($this->getContent(), (string) $needle)) {
            return parent::beforeSave();
        }
        throw new \Magento\Framework\Exception\LocalizedException(
            __('Make sure that pickup point content does not reference the pickup point itself.')
        );
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getIdentifier()];
    }

    public function getId()
    {
        return $this->getData(self::Tienda_ID);
    }

    public function getNombre()
    {
        return $this->getData(self::NOMBRE);
    }

    public function getDireccion()
    {
        return $this->getData(self::DIRECCION);
    }

    public function getLatitud()
    {
        return $this->getData(self::LATITUD);
    }

    public function getLongitud()
    {
        return $this->getData(self::LONGITUD);
    }

    public function getLocalidad()
    {
        return $this->getData(self::LOCALIDAD);
    }

    public function getImage()
    {
        return $this->getData(self::IMAGE);
    }

    public function getMetodoenvio()
    {
        return $this->getData(self::METODOENVIO);
    }

    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    public function isActive()
    {
        return (bool)$this->getData(self::IS_ACTIVE);
    }

    public function setId($id)
    {
        return $this->setData(self::Tienda_ID, $id);
    }

    public function setNombre($nombre   )
    {
        return $this->setData(self::NOMBRE, $nombre);
    }

    public function setDireccion($direccion)
    {
        return $this->setData(self::DIRECCION, $direccion);
    }

    public function setLatitud($latitud)
    {
        return $this->setData(self::LATITUD, $latitud);
    }

    public function setLongitud($longitud)
    {
        return $this->setData(self::LONGITUD, $longitud);
    }

    public function setLocalidad($localidad)
    {
        return $this->setData(self::LOCALIDAD, $localidad);
    }

    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    public function setMetodoenvio($options)
    {
        return $this->setData(self::METODOENVIO, $options);
    }

    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : $this->getData('store_id');
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    public function getAvailableOptions()
    {
        return [
            self::OPTION_0 => __('No Shipping Method'),
            self::OPTION_A => __('Metodo #1'),
            self::OPTION_B => __('Metodo #2'),
            self::OPTION_C => __('Metodo #3')
        ];
    }
}
