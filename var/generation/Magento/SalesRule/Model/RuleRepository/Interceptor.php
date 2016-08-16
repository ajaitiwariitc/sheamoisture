<?php
namespace Magento\SalesRule\Model\RuleRepository;

/**
 * Interceptor class for @see \Magento\SalesRule\Model\RuleRepository
 */
class Interceptor extends \Magento\SalesRule\Model\RuleRepository implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\SalesRule\Model\RuleFactory $ruleFactory, \Magento\SalesRule\Api\Data\RuleInterfaceFactory $ruleDataFactory, \Magento\SalesRule\Api\Data\ConditionInterfaceFactory $conditionDataFactory, \Magento\SalesRule\Model\Converter\ToDataModel $toDataModelConverter, \Magento\SalesRule\Model\Converter\ToModel $toModelConverter, \Magento\SalesRule\Api\Data\RuleSearchResultInterfaceFactory $searchResultFactory, \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor, \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory $ruleCollectionFactory, \Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor)
    {
        $this->___init();
        parent::__construct($ruleFactory, $ruleDataFactory, $conditionDataFactory, $toDataModelConverter, $toModelConverter, $searchResultFactory, $extensionAttributesJoinProcessor, $ruleCollectionFactory, $dataObjectProcessor);
    }

    /**
     * {@inheritdoc}
     */
    public function save(\Magento\SalesRule\Api\Data\RuleInterface $rule)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save($rule);
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getById($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getById');
        if (!$pluginInfo) {
            return parent::getById($id);
        } else {
            return $this->___callPlugins('getById', func_get_args(), $pluginInfo);
        }
    }
}
