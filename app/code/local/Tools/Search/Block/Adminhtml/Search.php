<?php
class Tools_Search_Block_Adminhtml_Search extends Mage_Adminhtml_Block_Widget_Container
{
  	protected $_result = "";
	protected $_children = 0;
  
  	public function __construct()
  	{
    	$this->_controller = 'adminhtml_search';
    	$this->_blockGroup = 'search';
    	$this->_result =  Mage::registry('amazonsearch');

    	parent::__construct();
  	}
  
	protected function _prepareLayout()
    { 
        return parent::_prepareLayout();
    }
    
	public function getAmazonSearch()     
    { 
       if (!$this->hasData('amazonsearch')) {
       		$this->setData('amazonsearch', $this->_result);
       }
       $_SESSION['search_result'] = serialize($this->_result);
       return $this->getData('amazonsearch');
    }
  
	public function getResultCount(){
    	if($this->_result){
    		return $this->_result[0]["total"];
    	}
    	return 0;
    }
    
    public function getTotalPage(){
    	if($this->_result){
    		return $this->_result[0]["totalpage"];
    	}
    	return 0;
    }
    
    public function getItemPage(){
    	if($this->_result){
    		return $this->_result[0]["itempage"];
    	}
    	return 0;
    }
    
     public function getCategoryTree(){
    	if (!$this->hasData('category_tree')) {
    		
       		$this->setData('category_tree', $this->getCategoryTreeHtml(Mage::registry('category_tree')));
       	}
    	return $this->getData('category_tree');
    }
    
    function getCategoryTreeHtml($resultCate){
    	$html = '';
    	$rootCate = $resultCate['children'];
    	if($rootCate){
    		for($i=0;$i<count($rootCate);$i++){
    			$cate = $rootCate[$i];
    			
	    		$html .= '<li class="x-tree-node">';
	    		if($cate['is_active'])
	    			$html .=	'<div class="x-tree-node-el folder active-category  x-tree-node-expanded">';
	    		else
	    			$html .=	'<div class="x-tree-node-el folder active-category  x-tree-node-expanded" style="color:#AAAAAA !important;">';
	    				
		  		$html .=  '<span class="x-tree-node-indent">
		  								<img src="'.$this->getJsUrl().'spacer.gif" class="x-tree-icon">
		  							</span>';
	    		if($i == count($rootCate)-1)
	    			$html .= '<img id="ext-gen22" src="'.$this->getJsUrl().'spacer.gif" class="x-tree-ec-icon x-tree-elbow-end">';
	    		else
	    			$html .= '<img id="ext-gen22" src="'.$this->getJsUrl().'spacer.gif" class="x-tree-ec-icon x-tree-elbow">';
		  			
	    		$html .='<input id="categories[]" class="l-tcb" type="checkbox" name="categories[]" value="'.$cate['category_id'].'">
		  							<span unselectable="on">'.$cate['name'].'</span>
		  					 </div>';
	    		if(count($cate['children'])>0){
	    			$html .= '<ul class="x-tree-node-ct" style="">';
	    			$html .= $this->getChildrenNote($cate['children']);
	    			$html .= '</ul>';
	    		}
		  		$html .='</li>';
    		
	  			$this->_children=0;
    		}
    		
    	}
    	return $html;
    }
    
    function getChildrenNote($cateChildren){
    	$html = '';
    	$this->_children++;
    	if($cateChildren){
    		for($i=0;$i<count($cateChildren);$i++){
    			$cate = $cateChildren[$i];
    			
	    		$html .= '<li class="x-tree-node">';
	    		if($cate['is_active'])
		  			$html .= '<div class="x-tree-node-el folder active-category  x-tree-node-leaf">';
		  		else
		  			$html .= '<div class="x-tree-node-el folder active-category  x-tree-node-leaf" style="color:#AAAAAA !important;">';
		  				
		  			$html .=  '<span class="x-tree-node-indent">
		  							<img src="'.$this->getJsUrl().'spacer.gif" class="x-tree-icon">';
	    								
	    		for($j=0;$j<$this->_children;$j++){
	    			$html .= '<img src="'.$this->getJsUrl().'spacer.gif" class="x-tree-elbow-line">';
	    		}
	    		$html .= '</span>';
		  							
		  		if($i == count($cateChildren)-1)
	    			$html .= '<img id="ext-gen55" src="'.$this->getJsUrl().'spacer.gif" class="x-tree-ec-icon x-tree-elbow-end">';
	    		else
	    			$html .= '<img id="ext-gen55" src="'.$this->getJsUrl().'spacer.gif" class="x-tree-ec-icon x-tree-elbow">';
		  		$html .='<input id="categories[]" class="l-tcb" type="checkbox" name="categories[]" value="'.$cate['category_id'].'">
		  							<span unselectable="on">'.$cate['name'].'</span>
		  						</div>';
	    		if(count($cate['children'])>0){
	    				
	    			$html .= '<ul class="x-tree-node-ct" style="">';
	    			$html .= $this->getChildrenNote($cate['children']);
	    			$html .= '</ul>';
	    			$this->_children--;
	    		}
		  		$html .='</li>';
    		}
    	}
    	return $html;
    }
}