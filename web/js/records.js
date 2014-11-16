var oTable = null;

function initDataTable(ajaxSource) {
	// check the existence of table on which we are going to apply datatable.
	if ($('#records').length > 0)
	{
		// Modify css for managing view.
		$('#container').removeClass('container');
		$('#container').css('margin', '20px');
		oTable =
		$('#records').dataTable(
		{
			"dom": '<"top"p><"clear">tir<"bottom"p>',
			"bProcessing": true,
			"bServerSide": true,
			"language": {
				"info": "Showing _START_ - _END_ of _MAX_"
			},
			"aoColumnDefs": [
				{"bSortable": false, "aTargets": [0]}
			],
			"aaSorting": [],
			"sAjaxSource": ajaxSource,
			"bStateSave": true,
			"fnInitComplete": function () {
				oTable.fnAdjustColumnSizing();
			},
			"fnServerData": function (sSource, aoData, fnCallback) {
				jQuery.getJSON(sSource, aoData, function (json) {
					fnCallback(json);

				});
			},
		});
	}
}
