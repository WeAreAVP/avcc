oFC = null;
function updateDataTable() {
	height = $(window).height() - 185;
	scrollHeight = height + 10;
//    console.log(index_column);
//    console.log(order_column);
	if ($('#records').length > 0)
	{
		oTable =
		$('#records').dataTable(
		{
			
			"processing": true,
			"serverSide": true,
			"ajax": tableSource,
			"fnServerData": function (sSource, aoData, fnCallback) {


				jQuery.getJSON(sSource, aoData, function (json) {


					fnCallback(json);

				});
			},
		});

	}
}

function getColumnOrder()
{
	var orderString = new Array;
	$('#records th').each(function (index)
	{
		if (index == 0)
		{
			orderString[index] = this.id;
		}
		else
		{
			if (!in_array(this.id, orderString, true))
			{
				orderString[index] = this.id;
			}
		}
	});

	return orderString;
}

function reOrderDropDown(columnArray)
{

	var columnNames = {
		checkbox_col: {english: 'checkbox_col', dutch: 'checkbox_col'},
		Organization: {english: 'Organization', dutch: 'Organisatie'},
		PID: {english: 'PID', dutch: 'PID'},
		Barcode: {english: 'Barcode', dutch: 'Barcode'},
		Title: {english: 'Title', dutch: 'Titel'},
		Creation_Date: {english: 'Creation Date', dutch: 'Datum'},
		Created_On: {english: 'Created On', dutch: 'Aangemaakt op'},
		Media_Duration: {english: 'Media Duration', dutch: 'Duur'},
		Status: {english: 'Status', dutch: 'Status'},
		Carrier_Type: {english: 'Carrier Type', dutch: 'Dragertype'},
		Carrier_Format: {english: 'Carrier Format', dutch: 'Dragerformaat'},
		Abraham_ID: {english: 'Abraham ID', dutch: 'Abraham ID'},
		Edition: {english: 'Edition', dutch: 'Edition'},
		Number: {english: 'Number', dutch: 'Number'},
		Number_Of_Pages: {english: 'Number of Pages', dutch: 'Aantal Pagina\'s '},
		Collection_Box_Barcode: {english: 'Collection Box Barcode', dutch: 'Barcode verzameldoos'},
		Carrier_Original_ID: {english: 'Carrier Original ID', dutch: 'Oorspronkelijk dragernummer'},
		Shipment: {english: 'Shipment', dutch: 'Verzending'},
		Batch: {english: 'Batch', dutch: 'Batch'},
		Imported: {english: 'Imported', dutch: 'Geïmporteerd'}

	}

//	var columnShowHide = new Array();
//	$('#show_hide_li a').each(function(index, id)
//	{
//		if ($('#' + this.id + ' i').css('display') == "none")
//		{
//			columnShowHide[index] = str_replace(' ', '_', $(this).data().text);
//		}
//
//	});
//
//	$('#show_hide_li').html('');
//	for (cnt in columnArray)
//	{
//		display = '';
//		if (in_array(columnArray[cnt], columnShowHide, true))
//		{
//			display = 'style="display:none;"';
//		}
//		hide = '';
//		if (columnArray[cnt] == 'checkbox_col')
//			hide = 'style="display:none;"'
//		if (ReadCookieValue('app_language') == 'dutch')
//			name = columnNames[columnArray[cnt]].dutch;
//		else
//			name = columnNames[columnArray[cnt]].english;
//		$('#show_hide_li').append('<li ' + hide + ' ><a href="javascript://;" data-text="' + columnArray[cnt] + '" onclick="showHideColumns(' + cnt + ');" id="' + cnt + '_column"><i class="icon-ok" ' + display + '></i>' + name + '</a></li>');
//
//	}
}

function in_array(needle, haystack, argStrict) {
	var key = '',
	strict = !!argStrict;

	if (strict) {
		for (key in haystack) {
			if (haystack[key] === needle) {
				return true;
			}
		}
	} else {
		for (key in haystack) {
			if (haystack[key] == needle) {
				return true;
			}
		}
	}

	return false;
}