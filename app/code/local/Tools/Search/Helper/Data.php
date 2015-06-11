<?php

class Tools_Search_Helper_Data extends Mage_Core_Helper_Abstract
{
	protected $_messages = array();
	
 	public static function getUrl($route='', $params=array())
    {
        return Mage::getModel('adminhtml/url')->getUrl($route, $params);
    }
	/**
     * Add Note message
     *
     * @param string $message
     * @return Mage_CatalogSearch_Helper_Data
     */
    public function addNoteMessage($message)
    {
        $this->_messages[] = $message;
        return $this;
    }
	
	public function getEscapedQueryText()
    {
        return $this->htmlEscape($this->getQueryText());
    }
    
    /**
     * Set Note messages
     *
     * @param array $messages
     * @return Mage_CatalogSearch_Helper_Data
     */
    public function setNoteMessages(array $messages)
    {
        $this->_messages = $messages;
        return $this;
    }

    /**
     * Retrieve Current Note messages
     *
     * @return array
     */
    public function getNoteMessages()
    {
        return $this->_messages;
    }
    
	public function saveCache($data, $id, $tags=array(), $lifeTime=false){
    	$this->_saveCache(serialize($data), $id, $tags, $lifeTime);
    }
    
	public function loadCache($id)
    {
        return unserialize($this->_loadCache($id));
    }
    
	public function removeCache($id)
    {
        return $this->_removeCache($id);
    }
}