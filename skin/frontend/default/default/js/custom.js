/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

magiczoomswatch = Class.create();
magiczoomswatch.prototype = {
    initialize: function() {

    },
    set_attribute_selected_value: function(attribute_id, optionId) {
        dropdown = document.getElementById('attribute' + attribute_id);
        for ( var i = 0; i < dropdown.options.length; i++ ) {
            if ( dropdown.options[i].value == optionId ) {
                dropdown.selectedIndex = i;
                var element=$('attribute'+attribute_id);
                spConfig.configureElement(element);
             //   MagicToolboxChangeOptionConfigurable(dropdown,'color');
                break;
            }
        }
        $j("#selected_value_attribute_"+attribute_id).text($j("#attribute"+attribute_id+" option:selected").text());
    },
    createLiNodeByImage:function(productId,image)
    {
        a = document.createElement('a');
        a.setAttribute('class','MagicThumb-swap');
        a.setAttribute('rev', image.base_image);
        a.setAttribute('href', image.hi_res);
        a.setAttribute('rel', "zoom-id: MagicZoomPlusImage"+productId+"; caption-source: a:title;");
        a.setAttribute('title', image.label);
        a.setAttribute('style',"outline: medium none; display: inline-block;");
        img = document.createElement("img");
        img.alt = "";
        img.src = image.thumbnail;
        a.appendChild(img);
        a.onclick = function() {
            MagicToolboxChangeSelector(this);
            return;
        }
        li  =  document.createElement('li');
        li.appendChild(a);
        return li;
    },
    setThumbnail:function(colorConfig, colorId)
    {
        imageArr = this.findImageArray(colorConfig, colorId);
    
        var tempLi;
        $j('#MagicToolboxSelectors'+productId+' ul').empty();
        if(  imageArr)
        {
            for(i=0; i<imageArr.length; i++)
            {
                tempLi = this.createLiNodeByImage(productId,imageArr[i]);
                $j('#MagicToolboxSelectors'+productId+' ul').append(tempLi );
            }

            $j('.color-image img').css({
                'border': '1px solid #A9D5EF'
            }); 
            $j("#icon_color" + colorId +' img' ).css({
                'border': '1px solid #024672'
            }); 

            MagicZoomPlus.refresh();
        }
    },
    findImageArray:function(colorConfig, colorId)
    {
        for(i=0; i<colorConfig.length; i++)
        {

            if(colorConfig[i].id==colorId)
                return colorConfig[i].image;
        }
        return 0;
    },
    setImageBaseOnMouseOver:function(colorId)
    {
        imageArr = this.findImageArray(colorConfig, colorId);
        if(imageArr[0])
        $j('#MagicZoomPlusImage'+productId+' img')[0].src=imageArr[0].base_image;
    },
    mouseOverAttributeItem:function(attribute_id,option_id,disabledFlag)
    {
     
        if(!disabledFlag)
        {
            if(typeof disabledFlag!=='undefined')
            {
                $j('#selected_value_attribute_'+attribute_id).text(this.changeLabelByDropdownAttrOption(attribute_id, option_id)+' (còn hàng)');
            }
        }
        else
            $j('#selected_value_attribute_'+attribute_id).text(this.changeLabelByDropdownAttrOption(attribute_id, option_id));
        if(attribute_id==colorAttributeId)
        {
            this.setImageBaseOnMouseOver(option_id); 
        }

    },				
    mouseOutAttributeItem:function(attribute_id)
    {
        if(attribute_id==colorAttributeId)
        {
            this.setImageBaseOnMouseOut();
            this.changeLabelByAttributeId(attribute_id);
        }
    }	,
    setImageBaseOnMouseOut:function()
    {
        $j('#MagicZoomPlusImage'+productId+' img')[0].src=baseImage;
    },
    setImageBaseByColorId:function(colorId)
    {
        baseImage= $j('#MagicZoomPlusImage'+productId+' img')[0].src;
        imageArr = this.findImageArray(colorConfig, colorId);
        if(imageArr[0])
            $j("#MagicZoomPlusImage"+productId)[0].href = imageArr[0].hi_res;
    },
    changeLabelByAttributeId:function(attribute_id)//only selected
    {
    
        if($j("#attribute"+attribute_id+" option:selected").val()!="")
            $j("#selected_value_attribute_"+attribute_id).text($j("#attribute"+attribute_id+" option:selected").text());
        else
            $j("#selected_value_attribute_"+attribute_id).text("");
    },
    changeLabelByDropdownAttrOption:function(attribute_id, option_id)
    {
        return $j("#attribute"+attribute_id+" option[value='"+option_id+"']").text();
    },
    createColorLiNodeByOption:function(attribute_id, id,value)
    {
        proxy = this;
        a = document.createElement('a');
        a.setAttribute('class','img-thumb');
        a.setAttribute('id','icon_color'+id);
        a.setAttribute('href', 'javascript:void(0)');
        a.setAttribute('title', 'Click để chọn ' + value);


        // a.setAttribute('style',"cursor:pointer; float:left; margin-left: 3px;");
        a.appendChild(document.createTextNode(value));
        a.onclick = function() {  
            proxy.set_attribute_selected_value(attribute_id,id); 
            proxy.setThumbnail(colorConfig, id);   
           // proxy.appendSizeAttr(); 
           if(secondAttribute)
           magiczoomswatch.appendSelectNode(secondAttribute);
           
            $j(".img-thumb").css({
                'border': '1px solid #d5d5d5',
                'padding': '2px 7px'
            });
            $j("#icon_color" + id ).css({
                'border': '2px solid #024672',
                'padding': '1px 6px'
            });	

            proxy.setImageBaseByColorId(id);

  
   


        }
      
        a.onmouseover = function() {
            proxy.mouseOverAttributeItem(attribute_id,id);
        //mouseOverAttributeItem(attribute_id,id);
        }
                			
        a.onmouseout = function() {
            proxy.mouseOutAttributeItem(attribute_id);
        //changeLabelByAttributeId(attribute_id);
        }
        li  =  document.createElement('li');
        li.appendChild(a);
        return li;
    },
    addColorNode:function()
    {
        colorAttrId = colorAttributeId;
        proxy = this;
        $j('.color-value').children().remove();
        $j("#attribute"+colorAttrId+" option").each(function()
        {
            if($j(this).val()!='')
            {			    
                li  =   proxy.createColorLiNodeByOption(colorAttrId,$j(this).val(),$j(this).text());
                $j('.color-value')[0].appendChild(li);
            }
        });
    },
    appendSizeAttr:function()
    {
        proxy=this;
        $j('#custom-586').empty();
        $j("#attribute586 option").each(function()
        {
            if($j(this).val()!='')
            {
                li = proxy.createSizeLiNodeByOption(586,$j(this).val(),$j(this).text());
                $j('#custom-586').append(li);
            }
        });
    },
    appendSelectNode:function(attribute_id)
    {
        proxy=this;
        $j('#custom-'+attribute_id).empty();
        if($j("#attribute"+attribute_id+" option").length>1)
        {
              var key = '';
            spConfig.settings.each(function(element){
                key += element.value + ',';
            });
            key = key.substr(0, key.length - 1);
            stStatus.onConfigure(key, spConfig.settings);

        $j("#attribute"+attribute_id+" option").each(function()
        {
             var disabledFlag = 0;
                 //  console.log($j(this).attr('value')+' '+$j(this).attr('disabled'));
                   if($j(this).attr('disabled')) disabledFlag=1 ;
            if($j(this).val()!='')
            {

                li = proxy.createLiNodeByOption(attribute_id,$j(this).val(),$j(this).text(),disabledFlag);
                $j('#custom-'+attribute_id).append(li);
            }
        });

       }

    },
    createLiNodeByOption:function(attribute_id, id,value,disabledFlag)
    {
        

        proxy = this;
        a = document.createElement('a');
        if(disabledFlag)
        {
            $j(a).attr("disabled","disabled");
            a.setAttribute('class','size-thumb out-stock-size');
        }
        else
        {
            a.setAttribute('class','size-thumb');
        }
        
        a.setAttribute('id','size_'+id);
            					
        a.setAttribute('href', 'javascript:void(0)');
        a.setAttribute('title', 'Click để chọn size: '+ value);
            
        //a.setAttribute('style',"cursor:pointer; float:left; margin-left: 3px;");
        a.appendChild(document.createTextNode(value));
        a.onclick = function() {  
            if(!disabledFlag)
            {
                 proxy.set_attribute_selected_value(attribute_id,id); 
                						
                $j(".size-thumb").css({
                    'border': '1px solid #D5D5D5',
                    'padding':'2px 7px'
                    
                });
                    
                $j("#size_" + id).css({
                    'border': '2px solid #024672',
                    'padding':'1px 6px'
                });
            }
        }
        //mouseOverAttributeItem(76,14563); setImageBaseOnMouseOver(14563); 
        a.onmouseover = function() {
            proxy.mouseOverAttributeItem(attribute_id,id,disabledFlag);
        }
        a.onmouseout = function() {
            proxy.changeLabelByAttributeId(attribute_id);
        }
        li  =  document.createElement('li');
        li.appendChild(a);
        return li;
    }

}




