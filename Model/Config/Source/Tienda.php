<?php
declare(strict_types=1);

namespace Orangecat\Puntorecogida\Model\Config\Source;

use Orangecat\Puntorecogida\Model\ResourceModel\Tienda\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class Block implements OptionSourceInterface
{
    private $options;

    private $collectionFactory;

    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = $this->collectionFactory->create()->toOptionIdArray();
        }
        //selected
        return $this->options;
    }
}
