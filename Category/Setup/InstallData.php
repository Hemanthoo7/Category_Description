<?php

namespace Dcw\Category\Setup;

use Magento\Framework\Setup\{
    ModuleContextInterface,
    ModuleDataSetupInterface,
    InstallDataInterface
};

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory) {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'short_description', [
            'type'     => 'text',
            'label'    => 'Short Description',
            'input'    => 'textarea',
            'source'   => '',
            'visible'  => true,
            'default'  => null,
            'required' => false,
            'global'   => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'is_wysiwyg_enabled' => true,
            'is_html_allowed_on_front' => true,
            'group'    => 'Content'
        ]);
    }
}
