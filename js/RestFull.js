var resultado;
function RestFullRequest(Source, Parameters, Callback, Message)
{
    Message = (Message) ? Message : 0;
    if(Message != 0)
    {
        MessageInit();
    }
	$.ajax({
		type: "POST",
		dataType: "JSON",
		url: Source,
		data: Parameters,
		async:"false",
		
	}).done(function(Response)
	{
	    if(Message != 0)
        {
            if(Message == 1)
                MessageSuccessSave();
            else if(Message == 2)
                MessageSuccessDelete();
        }


		if(Callback){
			var funcion = window[Callback];
			funcion(Response);
		}
	}).fail(function( jqXHR, textStatus, errorThrown)
	{	
		if(jqXHR.status == 409)
		{
			var obj = jQuery.parseJSON(jqXHR.responseText);
			var texto_error = "Error: ";

			$.each(obj, function(indice, value)
			{
				console.log(value);
				texto_error += " "+value['campo']+", ";
			});
			MessageError(texto_error);
		}else
		{
			MessageError("Error");
		}
		//console.log(obj);
	});
}

var AjaxPool = {
		AjaxCount: 0,
		AjaxItems: [],
		AjaxPush: function(ajaxPoolItem){
			this.AjaxCount++;
			ajaxPoolItem.id = this.AjaxCount;
			this.AjaxItems.push(ajaxPoolItem);
			if(ajaxPoolItem.label){
				var _id = ajaxPoolItem.id + '_' + ajaxPoolItem.type;
				$("#loading #loading-icon").append('<div id="'+_id+'"> '+ajaxPoolItem.label+'</div>');
			}
			this.AjaxCheck();
		},
		AjaxRemove: function(ajaxPoolItem){
			var _index = this.GetItemIndex(ajaxPoolItem);
			if(ajaxPoolItem.label){
				var _id = this.AjaxItems[_index].id + '_' +  ajaxPoolItem.type;
				$("#"+_id).remove();
			}
			this.AjaxItems.splice(_index,1);
			this.AjaxCheck();
		},
		AjaxCheck: function(){
			if(this.AjaxItems.length > 0){
				if(!$('#loading').is(':visible')){
					$("#loading").fadeIn();
				}
			}else{
				if($('#loading').is(':visible')){
					$("#loading").fadeOut();
				}
			}
		},
		GetItemIndex: function(item){
			for(var i in this.AjaxItems){
				if(this.AjaxItems[i].uri == item.uri && this.AjaxItems[i].type == item.type && this.AjaxItems[i].label == item.label){
					return i;
				}
			}
		}
	};
	
var RESTful2v = function(pUrl){
	this.url = pUrl || "";				
	this.dataType = "json";
	
	this.get = function(id, data, callbacks, label){ this.request("GET", id, data, callbacks, label); }
	this.put = function(id, data, callbacks, label){ this.request("PUT", id, data, callbacks, label); }
	this.post = function(data, callbacks, label){ this.request("POST", null, data, callbacks, label); }
	this.delete = function(id, data, callbacks, label){ this.request("DELETE", id, data, callbacks, label); }

	
	this.request = function(typeRequest,id, data, callbacks,label){			
		var fnCallbacks = {
			_success: callbacks._success || function(response){/*console.log(response);*/},
			_error: callbacks._error || function(jqXHR){
							var json = $.parseJSON(jqXHR.responseText);
							
							if(!json.code)
								MessageManager.show({code:'S03',data:"Hubo un problema al realizar la transacción, inténtelo de nuevo o contacte con soporte técnico."});
							else
								MessageManager.show(json);
			}
		}
		var url = "";

		if(id!== null && id !== ""){
			url = this.url+"/"+id;
		}else{
			url= this.url;
		}
		
		var item = {type:typeRequest,uri:url};
		if(label){
			item.label = label;
		}

		AjaxPool.AjaxPush(item);

		$.ajax({
			url: url,
			type: typeRequest,
			data: data,		
			success: function(response){ fnCallbacks._success(response);},
			error: function( jqXHR ){  fnCallbacks._error(jqXHR); },
			complete: function( jqXHR ){  AjaxPool.AjaxRemove(item); }
		});
	};
}




// JavaScript Document