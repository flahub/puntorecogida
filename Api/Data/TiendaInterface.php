<?php
namespace Orangecat\Puntorecogida\Api\Data;

interface TiendaInterface
{
    const Tienda_ID      = 'tienda_id';
    const NOMBRE         = 'nombre';
    const DIRECCION      = 'direccion';
    const LATITUD        = 'latitud';
    const LONGITUD       = 'longitud';
    const LOCALIDAD      = 'localidad';
    const IMAGE          = 'image';
    const METODOENVIO    = 'metodoenvio';
    const CONTENT       = 'content';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME   = 'update_time';
    const IS_ACTIVE     = 'is_active';

    public function getId();

    public function getNombre();

    public function getDireccion();

    public function getLatitud();

    public function getLongitud();

    public function getLocalidad();

    public function getImage();

    public function getMetodoenvio();

    public function getContent();

    public function getCreationTime();

    public function getUpdateTime();

    public function isActive();

    public function setId($id);

    public function setNombre($nombre);

    public function setDireccion($direccion);

    public function setLatitud($latitud);

    public function setLongitud($longitud);

    public function setLocalidad($localidad);

    public function setImage($image);

    public function setMetodoenvio($metodoenvio);

    public function setContent($content);

    public function setCreationTime($creationTime);

    public function setUpdateTime($updateTime);

    public function setIsActive($isActive);
}
