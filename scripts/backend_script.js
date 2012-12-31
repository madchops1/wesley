$(document).ready(function() {
	
	// NEW PAGE
	$('#newPage').click(function(e){	
		e.preventDefault();
		// INSERT PAGE AJAX
		$.ajax({
			type: 'POST',
			url: 'modules/pages/panel.php',
			data: { ajax: "1", newpage: "1" },
			dataType: "json",
			success: function(data){
				//alert(data);
				var newId = data;				
				// REFRESH PAGE
				location.reload();
			}
		});		
	});
	
	// PARENTS
	$('.ajax-parents-multiselect').live('change',function(e){
		//alert($(this).val());
		var values 	= $(this).val();
		var xid		= $(this).attr('xid');
		//values.each(function(){
		//	alert(this);
		//});
		var that = this;
		
		$(that).next('img').remove();  // remove pencil
		$(that).after('<img src="images/loader.gif" class="loader"/>');
		
		// alert(values);
		// INSERT PAGE AJAX
		$.ajax({
			type: 'POST',
			url: 'modules/pages/panel.php',
			data: { ajax: "1", updateparents: "1", id: xid, value: values},
			dataType: "json",
			success: function(data){
				
				//alert('made it');
				
				$(that).next('img').remove(); // remove loader
				$(that).after('<img src="images/accept.png" class="edit-pencil" />');
				$(that).next('img').fadeOut(1000,function(){ $(this).remove(); });
				
				//alert(data);
				//var newId = data;				
				// REFRESH PAGE
				//location.reload();
			}
		});		
	});
	
});