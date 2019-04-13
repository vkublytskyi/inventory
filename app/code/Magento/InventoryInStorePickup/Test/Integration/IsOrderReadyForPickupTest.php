<?php
declare(strict_types=1);

namespace Magento\InventoryInStorePickup\Test\Integration;

use Magento\InventoryInStorePickup\Model\IsOrderReadyForPickup;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

class IsOrderReadyForPickupTest extends TestCase
{
    /**
     * @var IsOrderReadyForPickup
     */
    private $isOrderReady;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    protected function setUp()
    {
        $objectManager = Bootstrap::getObjectManager();
        $this->isOrderReady = $objectManager->get(IsOrderReadyForPickup::class);
        $this->orderRepository = $objectManager->get(OrderRepositoryInterface::class);
    }

    /**
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/products.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/sources.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/stocks.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/stock_source_links.php
     * @magentoDataFixture ../../../../app/code/Magento/InventorySalesApi/Test/_files/websites_with_stores.php
     * @magentoDataFixture ../../../../app/code/Magento/InventorySalesApi/Test/_files/stock_website_sales_channels.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/source_items.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryIndexer/Test/_files/reindex_inventory.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryInStorePickup/Test/_files/create_in_store_pickup_quote_on_eu_website.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryInStorePickup/Test/_files/place_order.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryInStorePickup/Test/_files/ship_order.php
     *
     * @magentoDbIsolation disabled
     */
    public function testOrderCannotBeShipped()
    {

    }
}
