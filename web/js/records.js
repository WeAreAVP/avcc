/**
 * Class of managing Filtering and displaying of records.
 * 
 * @returns {Records}
 */
function Records() {
	/**
	 * Object of datatable.
	 */
//	this.oTable = null;
	/**
	 * URL for managing calls of datatable
	 * @type {string}
	 */
	var ajaxSource = null;
	/**
	 * Set the ajax URL of datatable.
	 * @param {string} source
	 * 
	 */
	this.setAjaxSource = function (source) {
		ajaxSource = source;

	}
	/**
	 * Initialize the datatable.
	 * 
	 */
	this.initDataTable = function () {
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
				"bStateSave": true
			});
		}

	}
}




