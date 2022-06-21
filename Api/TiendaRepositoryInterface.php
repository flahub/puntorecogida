<?php
namespace Orangecat\Puntorecogida\Api;

interface TiendaRepositoryInterface
{
    /**
     * Save punto de recogida.
     *
     * @param \Orangecat\PuntorecogidaApi\Data\TiendaInterface $block
     * @return \Orangecat\Puntorecogida\Api\Data\TiendaInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\TiendaInterface $tienda);

    /**
     * Retrieve punto de recogida.
     *
     * @param int $blockId
     * @return \Orangecat\Puntorecogida\Api\Data\TiendaInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($tiendaId);

    /**
     * Retrieve puntos de recogida que hacen match con el criterio especifico.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Orangecat\Puntorecogida\Api\Data\TiendaSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete punto de recogida.
     *
     * @param \Orangecat\Puntorecogida\Api\Data\TiendaInterface $block
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\TiendaInterface $tienda);

    /**
     * Delete punto de recogida por ID.
     *
     * @param int $tiendaId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($tiendaId);
}
