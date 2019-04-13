<?php
/**
 *  Copyright © Magento, Inc. All rights reserved.
 *  See COPYING.txt for license details.
 */
declare(strict_types=1);

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\ShipOrderInterface;
use Magento\TestFramework\Helper\Bootstrap;


$objectManager = Bootstrap::getObjectManager();
/** @var SearchCriteriaBuilder $searchCriteriaBuilder */
$searchCriteriaBuilder = $objectManager->get(SearchCriteriaBuilder::class);
$searchCriteria = $this->searchCriteriaBuilder
    ->addFilter('increment_id', 'in_store_pickup_test_order')
    ->create();
/** @var OrderInterface $createdOrder */
$createdOrder = current($this->orderRepository->getList($searchCriteria)->getItems());
/** @var ShipOrderInterface $shipOrder */
$shipOrder = $objectManager->get(ShipOrderInterface::class);
$shipOrder->execute($createdOrder->getEntityId());
