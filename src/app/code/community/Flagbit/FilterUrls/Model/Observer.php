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

class Flagbit_FilterUrls_Model_Observer
{
    const XML_PATH_SYSTEM_FLAGBIT_ENABLE_CRAWLER = 'system/filterurls/enable_crawler';

    public function filterurls_crawler()
    {
        if (!Mage::getStoreConfig(self::XML_PATH_SYSTEM_FLAGBIT_ENABLE_CRAWLER)) {
            return null;
        }

        /* @var $crawler Flagbit_FilterUrls_Model_Crawler */
        $crawler = Mage::getModel('filterurls/crawler');
        $crawler->crawlFilterUrls();
    }
}