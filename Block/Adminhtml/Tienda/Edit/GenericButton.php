<?php
namespace Orangecat\Puntorecogida\Block\Adminhtml\Tienda\Edit;

use Magento\Backend\Block\Widget\Context;
use Orangecat\Puntorecogida\Api\TiendaRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class GenericButton
{
    protected $context;
    protected $tiendaRepository;

    public function __construct(
        Context $context,
        TiendaRepositoryInterface $tiendaRepository
    ) {
        $this->context = $context;
        $this->tiendaRepository = $tiendaRepository;
    }

    public function getTiendaId()
    {
        try {
            return $this->tiendaRepository->getById(
                $this->context->getRequest()->getParam('tienda_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
