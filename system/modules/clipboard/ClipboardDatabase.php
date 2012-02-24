<?php

if (!defined('TL_ROOT'))
    die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  MEN AT WORK 2012
 * @package    clipboard
 * @license    GNU/GPL 2
 * @filesource
 */

/**
 * Class ClipboardDatabase
 */
class ClipboardDatabase extends Backend
{

    /**
     * Current object instance (Singleton)
     * @var ClipboardDatabase
     */
    protected static $objInstance;    

    /**
     * Prevent constructing the object (Singleton)
     */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * Prevent cloning of the object (Singleton)
     */
    final private function __clone()
    {
        
    }

    /**
     *
     * @return ClipboardDatabase 
     */
    public static function getInstance()
    {
        if (!is_object(self::$objInstance))
        {
            self::$objInstance = new ClipboardDatabase();
        }
        return self::$objInstance;
    }

    /**
     * Return page object from id
     * 
     * @param integer $intId
     * @return DB_Mysql_Result
     */
    public function getPageObject($intId)
    {
        $objDb = $this->Database
                ->prepare("
                    SELECT *
                    FROM `tl_page`
                    WHERE id = ?")
                ->limit(1)
                ->execute($intId);

        return $objDb;
    }
    
    /**
     * Return article object from id
     * 
     * @param integer $intId
     * @return DB_Mysql_Result
     */    
    public function getArticleObject($intId)
    {
        $objDb = $this->Database
                ->prepare("
                    SELECT *
                    FROM `tl_article`
                    WHERE id = ?")
                ->limit(1)
                ->execute($intId);

        return $objDb;        
    }
    
    /**
     * Return article object from pid
     * 
     * @param integer $intId
     * @return DB_Mysql_Result
     */    
    public function getArticleObjectFromPid($intId)
    {
        $objDb = $this->Database
                ->prepare("
                    SELECT *
                    FROM `tl_article`
                    WHERE pid = ?")
                ->limit(1)
                ->execute($intId);

        return $objDb;        
    }
    
    /**
     * Return article object from given child content id
     * 
     * @param integer $intId
     * @return DB_Mysql_Result
     */
    public function getArticleObjectFromContentId($intId)
    {
        $objDb = $this->Database
                ->prepare("
                    SELECT a.* 
                    FROM `tl_article` AS a
                    LEFT JOIN `tl_content` AS c
                    ON c.pid = a.id
                    WHERE c.id = ?")
                ->limit(1)
                ->execute($intId);
        
        return $objDb;        
    }


    /**
     * Return the current favorite as object
     * 
     * @param string $strTable
     * @param integer $intUserId
     * @return DB_Mysql_Result 
     */
    public function getFavorite($strTable, $intUserId)
    {
        $objDb = $this->Database
                ->prepare("
                    SELECT * 
                    FROM `tl_clipboard` 
                    WHERE str_table = ? 
                    AND favorite = 1 
                    AND `user_id` = ?")
                ->limit(1)
                ->execute($strTable, $intUserId);
        
        return $objDb;        
        
    }
    
    /**
     * Set clipboardentry to favorite and reset the old one
     * 
     * @param integer $intId
     * @param string $strPageType
     * @param integer $intUserId 
     */
    public function setNewFavorite($intId, $strPageType, $intUserId)
    {
        $this->Database
                ->prepare("
                    UPDATE `tl_clipboard` 
                    SET favorite = 0 
                    WHERE str_table = ? 
                    AND `user_id` = ?")
                ->execute('tl_' . $strPageType, $intUserId);
        
        $this->Database
                ->prepare("
                    UPDATE `tl_clipboard` 
                    SET favorite = 1 
                    WHERE id  = ? 
                    AND `user_id` = ?")
                ->execute($intId, $intUserId);        
    }
    
    /**
     * Return the current clipboard as object
     * 
     * @param string $strPageType
     * @param integer $intUserId
     * @return DB_Mysql_Result
     */
    public function getCurrentClipboard($strPageType, $intUserId)
    {
        $objDb = $this->Database
                ->prepare("
                    SELECT * 
                    FROM `tl_clipboard` 
                    WHERE str_table = ? 
                    AND user_id = ?")
                ->execute('tl_' . $strPageType, $intUserId);
        
        return $objDb;
    }
    
    /**
     * Copy given array set to clipboard and update favorite
     * 
     * @param array $arrSet 
     */
    public function copyToClipboard($arrSet)
    {
        $this->Database
                ->prepare("
                    UPDATE `tl_clipboard`
                    SET favorite = 0
                    WHERE str_table = ?
                    AND user_id = ?")
                ->execute($arrSet['str_table'], $arrSet['user_id']);
        
        $this->Database
                ->prepare("INSERT INTO `tl_clipboard` 
                    %s ON DUPLICATE KEY 
                    UPDATE favorite = 1")
                ->set($arrSet)
                ->execute();        
    }
    
    public function editClipboardEntry($strTitle, $intId, $intUserId)
    {
        $this->Database
                ->prepare("
                    UPDATE `tl_clipboard` 
                    SET title = ? 
                    WHERE id = ? 
                    AND `user_id` = ?")
                ->execute($strTitle, $intId, $intUserId);
    }
    
    /**
     * Delete the entry with the given id
     * 
     * @param integer $intId
     * @param integer $intUserId
     */
    public function deleteFromClipboard($intId, $intUserId)
    {
        $this->Database
                ->prepare("
                    DELETE FROM `tl_clipboard` 
                    WHERE `id` = ? 
                    AND `user_id` = ?")
                ->execute($intId, $intUserId);
    }

}

?>