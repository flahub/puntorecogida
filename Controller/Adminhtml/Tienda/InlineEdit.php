<?php
namespace Orangecat\Puntorecogida\Controller\Adminhtml\Tienda;

use Magento\Backend\App\Action\Context;
use Orangecat\Puntorecogida\Api\TiendaRepositoryInterface as TiendaRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Orangecat\Puntorecogida\Api\Data\TiendaInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Orangecat_Puntorecogida::tienda';

    protected $tiendaRepository;

    protected $jsonFactory;

    public function __construct(
        Context $context,
        TiendaRepository $tiendaRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->tiendaRepository = $tiendaRepository;
        $this->jsonFactory = $jsonFactory;
    }

    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $tiendaId) {
                    $tienda = $this->tiendaRepository->getById($tiendaId);
                    try {
                        $tienda->setData(array_merge($tienda->getData(), $postItems[$tiendaId]));
                        $this->tiendaRepository->save($tienda);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithTiendaId(
                            $tienda,
                            __($e->getMessage())
                        );
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    protected function getErrorWithTiendaId(TiendaInterface $tienda, $errorText)
    {
        return '[Tienda ID: ' . $tienda->getId() . '] ' . $errorText;
    }
}
