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
	
	/*+++++++++++++++++++++++++++++++++++++++++++*/
	
	var $actionBtn = $('#doaction');
	$actionBtn.on('click',function()
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
	
	$('.btnModalTesting').on('click',function()
	{
		html2canvas(document.body).then(canvas => 
		{
			var base64image = canvas.toDataURL("image/png");
			window.open(base64image , "_blank");
		});
	});
});