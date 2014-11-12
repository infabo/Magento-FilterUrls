<?php
/**
 * This file is part of the Flagbit_FilterUrls project.
 *
 * Flagbit_FilterUrls is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * PHP version 5
 *
 * @category Flagbit_FilterUrls
 * @package Flagbit_FilterUrls
 * @author Ingo Fabbri <if@newtown.at>
 * @copyright Copyright (c) 2014 Newtown-Web OG (http://www.newtown.at)
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.3.0
 * @since 0.3.0
 */

class Flagbit_FilterUrls_Model_Crawler
{
    const XML_PATH_SYSTEM_FILTERURLS_CRAWLER_THREADS = 'system/filterurls/crawler_threads';
    const USER_AGENT = 'MagentoCrawler';

    public function crawlFilterUrls()
    {
        $storeId = $this->_getStoreId();
        $this->_crawl($storeId);

        return $this;
    }

    private function _crawl($storeId)
    {
        $baseUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);

        $threads = (int)Mage::app()->getStore($storeId)->getConfig(self::XML_PATH_SYSTEM_FILTERURLS_CRAWLER_THREADS);
        $threads = !$threads ? 1 : $threads;

        $adapter = new Varien_Http_Adapter_Curl();
        $options = array(CURLOPT_USERAGENT => self::USER_AGENT);
        $urls = array();
        $urlsCount = 0;
        $totalCount = 0;

        /* @var $collection Flagbit_FilterUrls_Model_Resource_Mysql4_Url_Collection */
        $collection = Mage::getModel('filterurls/url')
            ->getCollection()
            ->addFieldToSelect('request_path')
            ->addFieldToFilter('store_id', $storeId);

        foreach ($collection as $item) {
            $urls[] = $url = Mage::helper('core')->escapeHtml(($baseUrl . $item->getRequestPath()));
            $urlsCount++;
            $totalCount++;
            if ($urlsCount == $threads) {
                $adapter->multiRequest($urls, $options);
                $urlsCount = 0;
                $urls = array();
            }
        }
        if (!empty($urls)) {
            $adapter->multiRequest($urls, $options);
        }
        $adapter->close();
    }

    /**
     * @return int|Mage_Core_Model_Store
     */
    private function _getStoreId()
    {
        return Mage::app()->getStore()->getId();
    }
}