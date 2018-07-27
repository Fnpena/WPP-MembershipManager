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
                var maxItemsPerPage = 4;
                var itemPerPage = 1;
                //var pdf = new jsPDF();
                var $currentValue= 1;
                
                const myPromise = new Promise(function(resolve,reject)
                                              {                    
                    var getDivId = $(".GCTemplate").map(function() {
                    return this.id;
                    }).get();


                    for( rw = 0; rw < getDivId.length ; rw++)
                    {
                        var $divContent = $('#'+getDivId[rw])[0];   

                        html2canvas($divContent).then(function (canvas) 
                        {
                        var base64image = canvas.toDataURL("image/png");

                        if(typeof(Storage)!=="undefined")
                        {
                            alert('1');
                            localStorage.setItem(getDivId[rw], base64image);
                        }

                        });
                    }
                    //return true;
                    resolve('fine');
                });
                
                myPromise.then(function whenOk(response){ alert('i am ready');})
//                var getDivId = $(".GCTemplate").map(function() {
//                return this.id;
//                }).get();
//            
//        
//                for( rw = 0; rw < getDivId.length ; rw++)
//                {
//                    var can = document.getElementById(getDivId[rw]);
//                    var ctx = can.getContext('2d');
//                    var base64image = can.toDataURL("image/png")
//                    
//                    $('#tab2').html("<img alt='view-result' id='my_viewer' />");
//				    $('#my_viewer').attr("src",base64image);
//				    $('#capture').hide(); 
//                }
//                
                //var can = document.getElementById('capture');
                //var ctx = can.getContext('2d');

                //ctx.fillRect(50,50,50,50);

                //var img = new Image();
                //alert(can.toDataURL("image/png"));
                //document.body.appendChild(img);
                //LoadList($currentValue);
                    
//                for(i = 0; i < 3; i++)
//                {
//                    alert('paso 2');
//                    alert(localStorage.getItem(InnerID[i]));
//                }
                            
                                    
                
                //alert('aqui');
                //pdf.save("download.pdf");
                
				/*var $divContent = $('#capture')[0]; 
				
				html2canvas($divContent).then(function (canvas) {
				var base64image = canvas.toDataURL("image/png");
				
                //Original Code for Testing
                //$('#tab2').html("<img alt='view-result' id='my_viewer' />");
				//$('#my_viewer').attr("src",base64image);
				//$('#capture').hide(); 
                
                var pdf = new jsPDF();
                pdf.addImage(base64image, 'PNG', 0, 0);
                pdf.save("download.pdf");
					
				});*/
			},
			Cancel: function(){ $(this).dialog('close'); }
		}
	});
    
    function LoadList()
    {
        var getDivId = $(".GCTemplate").map(function() {
                return this.id;
                }).get();
            
        
        for( rw = 0; rw < getDivId.length ; rw++)
        {
            var $divContent = $('#'+getDivId[rw])[0];   
            
            html2canvas($divContent).then(function (canvas) 
            {
                var base64image = canvas.toDataURL("image/png");
            
                if(typeof(Storage)!=="undefined")
                {
                    localStorage.setItem(getDivId[rw], base64image);
                    alert('paso 1');
                }

            });
        }
        return true;
    }
	
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