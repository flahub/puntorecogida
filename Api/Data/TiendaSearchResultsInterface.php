<?php
namespace Orangecat\Puntorecogida\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface TiendaSearchResultsInterface extends SearchResultsInterface
{
    public function getItems();

    public function setItems(array $tiendas);
}
