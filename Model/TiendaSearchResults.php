<?php
declare(strict_types=1);

namespace Orangecat\Puntorecogida\Model;

use Orangecat\Puntorecogida\Api\Data\TiendaSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

class TiendaSearchResults extends SearchResults implements TiendaSearchResultsInterface
{
}
