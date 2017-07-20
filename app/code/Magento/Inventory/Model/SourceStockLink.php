<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Inventory\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Inventory\Model\ResourceModel\SourceStockLink as SourceStockLinkResourceModel;

/**
 * Doesn't have API interface because this object is need only for internal module using
 *
 * @codeCoverageIgnore
 */
class SourceStockLink extends AbstractModel
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const LINK_ID = 'link_id';
    const SOURCE_ID = 'source_id';
    const STOCK_ID = 'stock_id';
    /**#@-*/

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(SourceStockLinkResourceModel::class);
    }

    /**
     * @inheritdoc
     */
    public function getLinkId()
    {
        return $this->getData(self::LINK_ID);
    }

    /**
     * @inheritdoc
     */
    public function setLinkId($linkId)
    {
        $this->setData(self::LINK_ID, $linkId);
    }

    /**
     * @inheritdoc
     */
    public function getSourceId()
    {
        return $this->getData(self::SOURCE_ID);
    }

    /**
     * @inheritdoc
     */
    public function setSourceId($sourceId)
    {
        $this->setData(self::SOURCE_ID, $sourceId);
    }

    /**
     * @inheritdoc
     */
    public function getStockId()
    {
        return $this->getData(self::STOCK_ID);
    }

    /**
     * @inheritdoc
     */
    public function setStockId($stockId)
    {
        $this->setData(self::STOCK_ID, $stockId);
    }
}
