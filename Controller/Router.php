<?php
declare(strict_types=1);

namespace Orangecat\Puntorecogida\Controller;

use Orangecat\Puntorecogida\Helper\Data;
use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RouterInterface;

/**
 * Class Router
 */
class Router implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    private $actionFactory;
    /**
     * @var Data
     */
    private $helper;
    /**
     * @var ResponseInterface
     */
    private $response;
    /**
     * Router constructor.
     *
     * @param ActionFactory $actionFactory
     * @param Data $helper
     * @param ResponseInterface $response
     */
    public function __construct(
        ActionFactory $actionFactory,
        Data $helper,
        ResponseInterface $response
    ) {
        $this->actionFactory = $actionFactory;
        $this->helper = $helper;
        $this->response = $response;
    }

    /**
     * @param RequestInterface $request
     * @return ActionInterface|null
     */
    public function match(RequestInterface $request): ?ActionInterface
    {
        $url = trim($request->getPathInfo(), '/');
        $identifier = explode('/', $url);
        $routeSize = count($identifier);

        if (!$routeSize || $routeSize >= 3) {
            return null;
        }

        $routerUrl = $this->helper->getRouterUrl();
        $module = 'puntorecogida';
        $controller = 'index';
        $action = 'index';
        $params = [];
        $continue = true;

        if ($identifier[0] == $routerUrl) {
            $request
                ->setModuleName($module)
                ->setControllerName($controller)
                ->setActionName($action)
                ->setParams($params)
                ->setDispatched(true);

            $continue = false;
        }
        if (!$continue) {
            return $this->actionFactory->create(
                Forward::class,
                [
                    'request' => $request
                ]
            );
        }
        /**
         * No hubo match con ninguna regla
         */
        return null;
    }
}
