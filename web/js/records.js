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
				retrieve: true,
				destroy: true,
				"language": {
					"info": "Showing _START_ - _END_ of _MAX_",
					"infoFiltered": ''
				},
				"aoColumnDefs": [
					{"bSortable": false, "aTargets": [0]}
				],
				"aaSorting": [],
				"sAjaxSource": ajaxSource,
				"bStateSave": true,
//				"fnInitComplete": function () {
//					this.oTable.fnAdjustColumnSizing();
//				},
				"fnServerData": function (sSource, aoData, fnCallback) {
					jQuery.getJSON(sSource, aoData, function (json) {
						fnCallback(json);

					});
				},
			});
		}

	}
	this.bindEvents = function () {
		$('input[name="mediaType[]"]').click(function () {
			checkParentFacet('media_type', $(this).attr('checked'));
		});
	}
	/**
	 * 
	 * @param {type} type
	 * @param {type} isChecked
	 * @returns {undefined}
	 */
	var checkParentFacet = function (type, isChecked) {
		//todo need to update function
		totalChecked = $('#total_checked').val();
		total = $('input:checked').length;
		if (total == 0)
			totalChecked = 0;
		if (isChecked == 'checked')
			totalChecked++;
		else
			totalChecked--;

		$('#total_checked').val(totalChecked);

		if ($('#parent_facet').val() == '' && totalChecked == 1)
			$('#parent_facet').val(type);
		else if (totalChecked == 0)
			$('#parent_facet').val('');
		this.filterRecords();
	}
	/**
	 * 
	 * @returns {undefined}
	 */
	this.filterRecords = function () {

		//send ajax call here.




	}
}




