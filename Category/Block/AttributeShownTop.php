<?php

namespace Dcw\Category\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Magento\Variable\Model\Variable;
use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;

class AttributeShownTop extends Template
{
    protected $registry;
    protected $variable;
    protected $attributeSetRepository;
    protected $stockRegistry;

    public function __construct(
        Context $context,
        Registry $registry,
        Variable $variable,
        AttributeSetRepositoryInterface $attributeSetRepository,
        StockRegistryInterface $stockRegistry
    )
    {
        $this->registry = $registry;
        $this->variable = $variable;
        $this->attributeSetRepository = $attributeSetRepository;
        $this->stockRegistry = $stockRegistry;
        parent::__construct($context);
    }

    /**
     * build the product attribute label & value array
     */
    public function getAttributeValues()
    {
        $productAttributeVals = [];
        $product = $this->registry->registry('current_product');//get current product
        
        $attributeSet = $this->attributeSetRepository->get($product->getAttributeSetId());
        $productAtributeSetName = $attributeSet->getAttributeSetName();

        $getAttributesList = $this->getShowTopAttributeCollection();

        if (isset($getAttributesList[$productAtributeSetName])) {
            $checkAttributeSet = $getAttributesList[$productAtributeSetName];

            if ($checkAttributeSet) {
                $productAttributes = explode(',', $checkAttributeSet);
                $count = 0;
                foreach($productAttributes as $attributes) {
                    if ($product->getAttributeText($attributes) != null) {
                        $productAttributeVals[$count]['value'] = $product->getAttributeText($attributes);
                        $productAttributeVals[$count]['label'] = $product->getResource()->getAttribute($attributes)->getFrontend()->getLabel($product);
                    }
                    $count++;
                }
            }
        }

        return $productAttributeVals;
    }

    /**
     * get the show attributes at top list from the system custom variables
     */
    public function getShowTopAttributeCollection()
    {
        $attributeList = [];
        $attributeShwonAtTop = $this->variable->loadByCode('attribute_shown_at_top');
        if ($attributeShwonAtTop && !empty($attributeShwonAtTop->getPlainValue())) {
            $shwonAtTopPlanText= explode("|", $attributeShwonAtTop->getPlainValue());

            foreach($shwonAtTopPlanText as $data) {
                $explodeData = explode("=>", $data);
                $attributeList[trim($explodeData[0])] = trim($explodeData[1]);
            }
        }

        return $attributeList;
    }

    /**
     * check current product stock status
     */
    public function checkCurrentProductStockStatus() {
        $product = $this->registry->registry('current_product');//get current product

        if ($product->getTypeId() == 'simple') {
            $stockItem = $this->stockRegistry->getStockItem($product->getId());
            $isInStock = $stockItem->getIsInStock();

            return $isInStock;
        }
        
        return true;
    }

    /**
     * check current product is simple or not
     */
    public function checkCurrentProductIsSimple() {
        $product = $this->registry->registry('current_product');//get current product

        if ($product->getTypeId() == 'simple') {
            return true;
        }
        
        return false;
    }
}

