<?php



Class Flagbit_FilterUrls_Model_Catalog_Layer_Filter_Category extends Mage_Catalog_Model_Layer_Filter_Category{


    /**
     * Creates the Category filter items based on id and System Config field
     *
     * @param   Zend_Controller_Request_Abstract $request
     * @param   Mage_Core_Block_Abstract $filterBlock
     * @return  Mage_Catalog_Model_Layer_Filter_Category
     */
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        //Get the filter from the id param
        $filter = (int) $request->getParam($this->getRequestVar(),$request->getParam('id'));

        if (!$filter) {
            return $this;
        }
        $this->_categoryId = $filter;
        //Get the top level category from System Configuration
        $iTopCategory=Mage::getStoreConfig('catalog/seo/root_category');
        Mage::register('current_category_filter', $this->getCategory(), true);

        $this->_appliedCategory = Mage::getModel('catalog/category')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($filter);
        //Don't add the top level Category to the catalog layered state
        if(!($this->_appliedCategory->getId()==$iTopCategory)){
            if ($this->_isValidCategory($this->_appliedCategory)) {
                $this->getLayer()->getProductCollection()
                    ->addCategoryFilter($this->_appliedCategory);

                $this->getLayer()->getState()->addFilter(
                    $this->_createItem($this->_appliedCategory->getName(), $filter)
                );
            }
        }

        return $this;

    }
}