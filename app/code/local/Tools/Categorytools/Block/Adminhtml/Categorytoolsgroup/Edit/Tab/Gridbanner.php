<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_Block_Adminhtml_Categorytoolsgroup_Edit_Tab_Gridcategorytools extends Mage_Adminhtml_Block_Widget_Container {

    /**
     * Set template
     */
    public function __construct() {
        parent::__construct();
        $this->setTemplate('categorytools/categorytoolss.phtml');
    }

    public function getTabsHtml() {
        return $this->getChildHtml('tabs');
    }

    /**
     * Prepare button and grid
     *
     */
    protected function _prepareLayout() {
        $this->setChild('tabs', $this->getLayout()->createBlock('categorytools/adminhtml_categorytoolsgroup_edit_tab_categorytools', 'categorytoolsgroup.grid.categorytools'));
        return parent::_prepareLayout();
    }

    public function getCategorytoolsgroupData() {
        return Mage::registry('categorytoolsgroup_data');
    }

    public function getCategorytoolssJson() {
        $categorytoolss = explode(',', $this->getCategorytoolsgroupData()->getCategorytoolsIds());
        if (!empty($categorytoolss) && isset($categorytoolss[0]) && $categorytoolss[0] != '') {
            $data = array();
            foreach ($categorytoolss as $element) {
                $data[$element] = $element;
            }
            return Zend_Json::encode($data);
        }
        return '{}';
    }

}
