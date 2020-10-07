function checkCurrentSession() 
{
return $.ajax({
	type: 'GET', 
	url: config.routes.checkCurrentSessionRoute, 
	data: {

	}
	});
}