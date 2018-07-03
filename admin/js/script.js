jQuery(document).ready(function($)
{
	/*+++++++++++++++++++++++++++++++++++++++++++
					Declare Modal
	+++++++++++++++++++++++++++++++++++++++++++++*/
	
	$('#dialog-viewer').dialog({
		autoOpen: false,
		height:500,
		width:650,
		modal: true,
		buttons:
		{
			"Export to PDF": function()
			{
				var $divContent = $('#capture')[0]; 
				
				html2canvas($divContent).then(function (canvas) {
				var base64image = canvas.toDataURL("image/png");
				
				$('#my_logo').attr("src",base64image);
				$('#capture').hide(); 
					
				});
			},
			Cancel: function(){ $(this).dialog('close'); }
		}
	});
	
	/*+++++++++++++++++++++++++++++++++++++++++++
	Name: ActionBtn Event
	Description: This event raise when apply button
	from bulk actions
	+++++++++++++++++++++++++++++++++++++++++++++*/
	
	var $actionBtn = $('#doaction');
	$actionBtn.on('click',function()
	{
		//This code extract all checked checkbox in the table
		var $results_tx = $('.cbk_id:checked').map(function()
		{
			return $(this).val();
		});
     	
		$.ajax
		({
			url: gims_testing.url,
			method:'POST',
			dataType:'json',
			data:
			{
				action: 'gims_generateCard',
				nonce: gims_testing.myvalidator,
				requested_ids:$results_tx.get()
			},
			success: function(data)
			{
				$('#dialog-viewer').dialog('open');
				$('.dialog-viewer-content').html(data.response_data);
			}
		});
		return false;
	});
	
	/*+++++++++++++++++++++++++++++++++++++++++++
	Name: ActionBtn2 Event
	Description: This event raise when apply button
	from bulk actions. this event is unique for lower
	action btn 2
	+++++++++++++++++++++++++++++++++++++++++++++*/
	
	var $actionBtn2 = $('#doaction2');
	$actionBtn2.on('click',function()
	{
		var $results_tx = $('.cbk_id:checked').map(function()
		{
			return $(this).val();
		});
		
		$.ajax
		({
			url: gims_testing.url,
			method:'POST',
			dataType:'json',
			data:
			{
				action: 'gims_generateCard',
				nonce: gims_testing.myvalidator,
				requested_ids:$results_tx.get()
			},
			success: function(data)
			{
				$('#dialog-viewer').dialog('open');
				$('.dialog-viewer-content').html(data.response_data);
			}
		});
		return false;
	});
	
/*	function getCleanPath(url)
	{
		var local = /localhost/;
		if(local.test(url))
		{
			var url_pathname = location.pathname,
				indexPos = url_pathname.indexOf('wp-admin'),
				url_pos  = url_pathname.indexOf(0,indexPos),
				url_delete = location.protocol + location.host + url_pos;
				
			return url_pos + url.replace(url_delete,'');
		}
		else
		{
			var url_domain = location.protocol + '//' + location.hostname;
			return url.replace(url_domain,'');
		}
	}*/
});