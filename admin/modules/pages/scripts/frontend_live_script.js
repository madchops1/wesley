// GET CURRENT PAGE WIDGETS FOR EACH SPOT
// Now there is only one spot
$('.wesley-spot').each(function()
{
	var that = this;
	// AJAX CURRENT WIDGETS
	$.ajax(
		{
			type:	'POST',
			url:	'/admin/modules/pages/panel.php',
			data:	{
						ajax: "1",
						getwidgets: "1",
						page:$("#wesley-field-page").val(),
						spot:$(this).attr('id')
					},
			success: function(data){
				$(that).prepend(data);
			}
		}
	);
});