<?php
namespace Orangecat\Puntorecogida\Model\Config\Source;
use Magento\Cms\Model\ResourceModel\Block\CollectionFactory;

class Cms implements \Magento\Framework\Option\ArrayInterface
{
    protected $_blockCollectionFactory;

    public function __construct(
        CollectionFactory $blockCollectionFactory
    ) {
        $this->_blockCollectionFactory = $blockCollectionFactory;
    }

    public function toOptionArray()
    {
        /**
         * @return array
         */
        $res = [];
        $options = $this->_blockCollectionFactory->create()->load();
        $i = 0;
        foreach ($options as $item) {
            $res[$i]["value"] = $item->getData("identifier");
            $res[$i]["label"] = $item->getData("title");
            $i++;
        }
        array_unshift($res, ['value' => '', 'label' => __('Please select a static block.')]);
        return $res;
    }
}
