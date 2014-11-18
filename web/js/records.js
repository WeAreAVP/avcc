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
    var pageUrl = null;
    var selfObj = this;
    /**
     * Set the ajax URL of datatable.
     * @param {string} source
     * 
     */
    this.setAjaxSource = function (source) {
        ajaxSource = source;

    }
    /**
     * Set the page url.
     * @param {string} url
     * 
     */
    this.setPageUrl = function (url) {
        pageUrl = url;

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
    /**
     * 
     * @returns {Boolean}
     */
    this.bindEvents = function () {
        $('input[name="mediaType[]"]').click(function () {
            checkParentFacet('media_type', $(this).attr('checked'));
        });
        return true;
    }
    /**
     * 
     * @param {type} type
     * @param {type} isChecked
     * @returns {undefined}
     */
    var checkParentFacet = function (type, isChecked) {
        //todo need to update function
        var totalChecked = $('#total_checked').val();
        var total = $('#facet_sidebar input:checked').length;
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
        filterRecords();
    }
    /**
     * 
     * @returns {undefined}
     */
    var filterRecords = function () {       
        $.ajax({
            type: 'POST',
            url: pageUrl,
            data: $('#formSearch').serialize(),
            dataType: 'json',
            success: function (response)
            {
                selfObj.initDataTable();
                window.location.reload();
                
            }

        });




    }
}




