<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\TargetRule\Block\Adminhtml\Targetrule\Edit;

/**
 * Enterprise TargetRule left-navigation block
 *
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('targetrule_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Product Rule Information'));
    }
}
