<?php
namespace Orangecat\Puntorecogida\Model\Tienda\Source;

use Magento\Framework\Data\OptionSourceInterface;

class IsActive implements OptionSourceInterface
{
    protected $puntorecogidaTienda;

    public function __construct(\Orangecat\Puntorecogida\Model\Tienda $puntorecogidaTienda)
    {
        $this->puntorecogidaTienda = $puntorecogidaTienda;
    }

    public function toOptionArray()
    {
        $availableOptions = $this->puntorecogidaTienda->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
