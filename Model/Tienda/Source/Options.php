<?php
namespace Orangecat\Puntorecogida\Model\Tienda\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    protected $puntorecogidaTienda;

    public function __construct(\Orangecat\Puntorecogida\Model\Tienda $puntorecogidaTienda)
    {
        $this->puntorecogidaTienda = $puntorecogidaTienda;
    }

    public function toOptionArray()
    {
        $availableOptions = $this->puntorecogidaTienda->getAvailableOptions();
        $options = [];
        foreach ($availableOptions as $key => $value) {

            $options[] = [
                'label' => $value->getText(),
                'value' => $key,
            ];
        }

        return $options;
    }
}
