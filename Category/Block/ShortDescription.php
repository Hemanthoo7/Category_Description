<?php

namespace Dcw\Category\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;

class ShortDescription extends Template
{
    protected $registry;

    public function __construct(
        Context $context,
        Registry $registry
    )
    {
        $this->registry = $registry;
        parent::__construct($context);
    }

    public function getShortDescription()
    {
        $category = $this->registry->registry('current_category');//get current category
        
        return $category->getData('short_description');
    }
}
