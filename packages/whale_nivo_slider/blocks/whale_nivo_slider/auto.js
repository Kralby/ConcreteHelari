var SlideshowBlock = {
	
	init:function(){},	

	fillData:function(but){ 
		var link_href= $(but).attr('href');
		var typ= $(but).attr('typ');//typ: t: fiill titles, d: fill descriptions
		var fIds = $('input[name=itemFIDs\\[\\]]').not('div#itemRowTemplateWrap input').map(function() {
				  return $(this).attr("value");
				}).get().join(',');
		
		if(fIds.length<1){ 
			alert(ccm_t('no-image')); //no image selected yet
		}else{
			$.getJSON(link_href, {'fIds':fIds},function(data) {
				i=0;
				if(typ=='t'){
					targets = $('input[id=itemTitle\\[\\]]').not('div#itemRowTemplateWrap input');
					targets.each(function(){    
						$(this).val(data[i].imageTitle);
						i++;
				    });
				}
				if(typ=='d'){
					targets = $('textarea[id=itemDesc\\[\\]]').not('div#itemRowTemplateWrap textarea');
					targets.each(function(){    
						$(this).val(data[i].imageDesc);
						i++;
				    });
				}
			});
		}
	},
	
	chooseItem:function(){ 
		ccm_launchFileManager('&fType=' + ccmi18n_filemanager.FTYPE_IMAGE);
	},
	
	showImages:function(){
		$("#ccm-slideshowBlock-itemRows").show();
		$("#ccm-slideshowBlock-chooseItem").show();
		$("#ccm-slideshowBlock-fsRow").hide();
	},

	selectObj:function(obj){
		if (obj.fsID != undefined) {
			$("#ccm-slideshowBlock-fsRow input[name=fsID]").attr("value", obj.fsID);
			$("#ccm-slideshowBlock-fsRow input[name=fsName]").attr("value", obj.fsName);
			$("#ccm-slideshowBlock-fsRow .ccm-slideshowBlock-fsName").text(obj.fsName);
		} else {
			this.addNewItem(obj.fID, obj.thumbnailLevel1, obj.title);
		}
	},

	addItems:0, 
	addNewItem: function(fID, thumbPath, title) { 
		this.addItems--; //negative counter - so it doesn't compete with real slideshowItemIds
		var itemId=this.addItems;
		var templateHTML=$('#itemRowTemplateWrap .ccm-slideshowBlock-itemRow').html().replace(/tempFID/g,fID);
		templateHTML=templateHTML.replace(/tempThumbPath/g,thumbPath);
		templateHTML=templateHTML.replace(/tempFilename/g,title);
		templateHTML=templateHTML.replace(/tempItemId/g,itemId);
		var itemRow = document.createElement("div");
		itemRow.innerHTML=templateHTML;
		itemRow.id='ccm-slideshowBlock-itemRow'+parseInt(itemId);	
		itemRow.className='ccm-slideshowBlock-itemRow';
		document.getElementById('ccm-slideshowBlock-itemRows').appendChild(itemRow);
		var bgRow=$('#ccm-slideshowBlock-itemRow'+parseInt(fID)+' .backgroundRow');
		bgRow.css('background','url('+escape(thumbPath)+') no-repeat left top');
		
		$("a.ccm-sitemap-select-page").unbind();
		$("a.ccm-sitemap-select-page").dialog();
		$("a.ccm-sitemap-select-page").click(function() {
				ccmActivePageField = this;
		});
	},
	
	removeItem: function(fID){
		$('#ccm-slideshowBlock-itemRow'+fID).remove();
	},
	
	moveUp:function(fID){
		var thisItem=$('#ccm-slideshowBlock-itemRow'+fID);
		var qIDs=this.serialize();
		var previousQID=0;
		for(var i=0;i<qIDs.length;i++){
			if(qIDs[i]==fID){
				if(previousQID==0) break; 
				thisItem.after($('#ccm-slideshowBlock-itemRow'+previousQID));
				break;
			}
			previousQID=qIDs[i];
		}	 
	},
	moveDown:function(fID){
		var thisItem=$('#ccm-slideshowBlock-itemRow'+fID);
		var qIDs=this.serialize();
		var thisQIDfound=0;
		for(var i=0;i<qIDs.length;i++){
			if(qIDs[i]==fID){
				thisQIDfound=1;
				continue;
			}
			if(thisQIDfound){
				$('#ccm-slideshowBlock-itemRow'+qIDs[i]).after(thisItem);
				break;
			}
		} 
	},
	serialize:function(){
		var t = document.getElementById("ccm-slideshowBlock-itemRows");
		var qIDs=[];
		for(var i=0;i<t.childNodes.length;i++){ 
			if( t.childNodes[i].className && t.childNodes[i].className.indexOf('ccm-slideshowBlock-itemRow')>=0 ){ 
				var qID=t.childNodes[i].id.replace('ccm-slideshowBlock-itemRow','');
				qIDs.push(qID);
			}
		}
		return qIDs;
	},	

	validate:function(){
		var failed=0; 
		
		qIDs=this.serialize();
		if( qIDs.length<2 ){
			alert(ccm_t('choose-min-2'));
			$('#ccm-slideshowBlock-AddItem').focus();
			failed=1;
		}	
		if(failed){
			ccm_isBlockError=1;
			return false;
		}
		return true;
	} 
}


ccmValidateBlockForm = function() { return SlideshowBlock.validate(); }
ccm_chooseAsset = function(obj) { SlideshowBlock.selectObj(obj); }


	sliderTabSetup = function() {
		$('ul#ccm-slider-tabs li a').each( function(num,el){ 
			el.onclick=function(){
				var pane=this.id.replace('ccm-slider-tab-','');
				sliderShowPane(pane);
			}
		});		
	}
	
	sliderShowPane = function (pane){
		$('ul#ccm-slider-tabs li').each(function(num,el){ $(el).removeClass('ccm-nav-active') });
		$(document.getElementById('ccm-slider-tab-'+pane).parentNode).addClass('ccm-nav-active');
		$('div.ccm-sliderPane').each(function(num,el){ el.style.display='none'; });
		$('#ccm-sliderPane-'+pane).css('display','block');
		//if(pane=='preview') reloadPreview(document.blockForm);
	}
	
	$(function() {	
		sliderTabSetup();		
	});

