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
    var Filters = new Object();
    var customFieldName = 'All';
    var customColumnName = 'all';
    var ajaxSaveStateUrl = null;
    var selectAllRecords = false;
    var totalRecords = 0;
    var totalCurrentPageRecords = 0;
    var ajaxExportUrl = null;
    var successMsg = null;
    var errorMsg = null;
    var popupHeading = null;
    var bulkUrl = null;
    var deleteUrl = null;
    /**
     * Set the ajax URL of datatable.
     * @param {string} source
     * 
     */
    this.setAjaxSource = function (source) {
        ajaxSource = source;

    }
    /**
     * Set the ajax URL for selected rows of datatable.
     * @param {string} source
     * 
     */
    this.setAjaxSaveStateUrl = function (source) {
        ajaxSaveStateUrl = source;

    }
    /**
     * Set the ajax URL to save export request in db.
     * @param {string} source
     * 
     */
    this.setAjaxExportUrl = function (source) {
        ajaxExportUrl = source;

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
     * Set the bulk url.
     * @param {string} url
     * 
     */
    this.setBulkUrl = function (url) {
        bulkUrl = url;

    }

    /**
     * Set the bulk url.
     * @param {string} url
     * 
     */
    this.setDeleteUrl = function (url) {
        deleteUrl = url;

    }
    /**
     * Set the popup message.
     * @param {string} success_msg
     * 
     */
    this.setSuccessMsg = function (success_msg) {
        successMsg = success_msg;

    }
    /**
     * Set the error merge file message.
     * @param {string} error_msg
     * 
     */
    this.setErrorMsg = function (error_msg) {
        errorMsg = error_msg;

    }
    /**
     * Set the heading for popup.
     * @param {string} heading
     * 
     */
    this.setPopupHeading = function (heading) {
        popupHeading = heading;

    }
    /**
     * Initialize the datatable.
     * 
     */
    this.initDataTable = function (filter) {
        // check the existence of table on which we are going to apply datatable.
        if ($('#records').length > 0)
        {
            // Modify css for managing view.
//			$('#container').removeClass('container');
//			$('#container').css('margin', '20px');
            var selected = [];
            oTable =
                    $('#records').dataTable(
                    {
                        "dom": '<"top"p><"clear">tir<"bottom"p>',
                        "bProcessing": true,
                        "bServerSide": true,
                        retrieve: true,
                        destroy: true,
                        "iDisplayLength": 100,
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
                                selfObj.totalRecords = json.iTotalDisplayRecords;
                                selfObj.totalCurrentPageRecords = json.iTotalRecords;
                            });
                        },
                        "createdRow": function (row, data, index) {
                            var input = $(row).find("td:first").html();
                            row.id = "row-" + $(input).attr('value');
                        },
//                        "ajax": ajaxSaveStateUrl,
                        "rowCallback": function (row, data) {
                            if ($(data[0]).attr("checked") == "checked") {
                                $(row).addClass("selected");
                            } else {
                                $(this).removeClass('selected');
                            }
                        }
                    });
            if (filter == 1) {
                oTable.fnPageChange("first", true);
            }
            $('#records tbody').on('click', 'tr', function () {
//                var id = this.id;
//                var rowid = id.split('-');
//                var index = $.inArray(rowid[1], selected);
//                if (index === -1) {
//                    selected.push(rowid[1]);
//                } else {
//                    selected.splice(index, 1);
//                }
//                console.log(rowid);
//                $(this).toggleClass('selected', function () {
//                    var input = $("#" + id + " td:first").html();
//                    if ($(this).hasClass('selected') === true) {
//                        $("#" + $(input).attr('id')).attr("checked", "checked");
//                        $("#" + $(input).attr('id')).prop("checked", true);
//                    } else {
//                        $("#" + $(input).attr('id')).removeAttr("checked");
//                        $("#" + $(input).attr('id')).prop("checked", false);
//                        $(this).removeClass('selected');
//                    }
//                    console.log($(input).attr('value'));
//                    if ($("#" + $(input).attr('id')).attr("checked")=="checked" || $("#" + $(input).attr('id')).prop("checked", true)) {
//                        selfObj.saveState($(input).attr('value'));
//                    }else if ($("#" + $(input).attr('id')).attr("checked")=="" || $("#" + $(input).attr('id')).prop("checked", false)){
//                        selfObj.saveState($(input).attr('value'));
//                    }
//                });

            });
        }

    }
    /**
     * 
     * @returns {Boolean}
     */
    this.bindEvents = function () {
        selfObj.isAnySearch();
        $('input[name="mediaType[]"]').click(function () {
            checkParentFacet('media_type', $(this).prop("checked"));
        });
        $('input[name="commercial[]"]').click(function () {
            checkParentFacet('commercial', $(this).prop('checked'));
        });
        $('input[name="format[]"]').click(function () {
            checkParentFacet('format', $(this).prop('checked'));
        });
        $('input[name="base[]"]').click(function () {
            checkParentFacet('base', $(this).prop('checked'));
        });
        $('input[name="recordingStandard[]"]').click(function () {
            checkParentFacet('recordingStandard', $(this).prop('checked'));
        });
        $('input[name="printType[]"]').click(function () {
            checkParentFacet('printType', $(this).prop('checked'));
        });
        $('input[name="project[]"]').click(function () {
            checkParentFacet('project', $(this).prop('checked'));
        });
        $('input[name="reelDiameter[]"]').click(function () {
            checkParentFacet('reelDiameter', $(this).prop('checked'));
        });
        $('input[name="discDiameter[]"]').click(function () {
            checkParentFacet('discDiameter', $(this).prop('checked'));
        });
        $('input[name="acidDetection[]"]').click(function () {
            checkParentFacet('acidDetection', $(this).prop('checked'));
        });
        $('input[name="collectionName[]"]').click(function () {
            checkParentFacet('collectionName', $(this).prop('checked'));
        });
        initTriStateCheckBox('is_review_check', 'is_review_check_state', true);
        $('#is_review_check').click(function () {
            var totalChecked = $('#total_checked').val();
            var containerId = $(this).attr('id');
            var currentVal = $('#' + containerId + '_state').val();
            if (currentVal > 0 && totalChecked == 0)
            {
                totalChecked++;
                $('#parent_facet').val(containerId);
                $('#total_checked').val(totalChecked);
            }
            if (currentVal == 0 && $('#parent_facet').val() == containerId) {
                totalChecked--;
                $('#parent_facet').val('');
                $('#total_checked').val(totalChecked);
            }
            filterRecords();
        });
        $('#reset_all').click(function () {
            selfObj.resetAll();
        });

        selfObj.addCustomToken();
        $('#addKeyword').click(function () {
            selfObj.addKeyword();
        });
        $('#keywordSearch').keypress(function (e) {
            if (e.which == 13) {
                selfObj.addKeyword();
            }
        });

        selfObj.removeFilter();
        selfObj.removeKeywordFilter();
        selfObj.selectCurrentPageRecords();
        selfObj.selectAllRecordsChk();
        selfObj.clearSelection();
        selfObj.exportRecords();
        selfObj.exportRequest();
        selfObj.closeClicked();
        selfObj.exportMergeRequest();
        selfObj.importRequest();
//        selfObj.showMergMsg();
        selfObj.showMsg();
        selfObj.validateRecords();
        selfObj.checkBoxes();
        selfObj.deleteRecords();
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
        if (isChecked)
            totalChecked++;
        else
            totalChecked--;
        if (totalChecked < 0)
            totalChecked = 0;
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
        $.blockUI({
            message: 'Please wait...',
            css: {
                border: 'none',
                padding: '15px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff',
                zIndex: 999999
            }
        });
        $.ajax({
            type: 'GET',
            url: pageUrl,
            data: $('#formSearch').serialize(),
            dataType: 'json',
            success: function (response)
            {
                $("#recordsContainer").html(response.html);
                var source = $('#metro-js').attr('src');
                var script = document.createElement("script");
                script.type = "text/javascript";
                script.src = source;
                script.id = "metro-js";
                $("#recordsContainer").append(script);
                selfObj.initDataTable(1);
                selfObj.bindEvents();
                $('body').scrollTop(0);
                $.unblockUI();
            }

        });
    }

    /**
     * 
     * @returns {undefined}
     */
    this.addCustomToken = function () {
        $('.customToken').click(function () {
            customFieldName = $(this).attr('data-fieldName');
            customColumnName = $(this).attr('data-columnName');
            html = '<li><a href="javascript://;" id="reset_keyword_field" class="resetToken" data-fieldName="Reset"  data-columnName="" style="font-size: 12px!important;">Reset</a></li>';
            if ($("#reset_keyword_field").length == 0 && customColumnName != "") {
                $("#keyword_menu").prepend(html);
            }
            $('#limit_field_text').html(customFieldName);
        });

        $("#keyword_menu").on('click', 'li a.resetToken', function () {
            customFieldName = $(this).attr('data-fieldName');
            customColumnName = 'all';
            $("#reset_keyword_field").remove();
            customFieldName = "Search";
            $('#limit_field_text').html(customFieldName);
        });
    }

    this.addKeyword = function () {
        if ($('#keywordSearch').val() != '') {
            if ($('#facet_keyword_search').val() != '' && $('#facet_keyword_search').val() != '""') {
                Filters = JSON.parse($('#facet_keyword_search').val());
            }
            else {
                Filters = new Array();
            }
            for (x in Filters) {
                if (Filters[x].value == $('#keywordSearch').val()) {
                    alert($('#keywordSearch').val() + " filter is already applied.");
                    return false;
                }
            }
            var temp = {};
            temp.value = $('#keywordSearch').val();
            temp.type = customColumnName;
            Filters.push(temp);

            $('#facet_keyword_search').val(JSON.stringify(Filters));
            customFieldName = 'All';
            customColumnName = 'all';
            filterRecords();
        }
    }

    this.removeFilter = function () {
        $('.delFilter').click(function () {
            elementID = $(this).attr('data-elementId');
            type = $(this).attr('data-type');
            $('#' + elementID).prop('checked', false);
            checkParentFacet(type);
        });
    }
    this.removeKeywordFilter = function () {
        $('.deleteKeyword').click(function () {
            var index = $.trim($(this).data().index);
            Filters = JSON.parse($('#facet_keyword_search').val());
            delete (Filters[index]);
            Filters.splice(index, 1);
            $('#facet_keyword_search').val(JSON.stringify(Filters));
            filterRecords();
        });

    }
    this.resetAll = function () {
        $('#formSearch').find('input:hidden, input:text, select').val('');
        $('#formSearch').find('input:radio, input:checkbox')
                .removeAttr('checked').removeAttr('selected');
        filterRecords();
    }
    this.isAnySearch = function () {
        if ($('.search_keys').length > 0) {
            $('#reset_all').show();
        }
        else {
            $('#reset_all').hide();

        }

    }

    this.selectCurrentPageRecords = function () {
        $('#selectAll').click(function () {
            if ($(this).prop('checked') == true) {
                selfObj.selectAllRecords = true;
                $('input[name=record_checkbox]').attr('checked', 'checked');
                $('input[name=record_checkbox]').prop('checked', true);
                if (selfObj.totalRecords > 0 && selfObj.totalRecords > selfObj.totalCurrentPageRecords) {
                    var html = '';
                    html += '<span id="div-records-on-page">All <span id="records-on-page">' + selfObj.totalCurrentPageRecords + '</span> records on this page selected.</span>';
                    html += ' <a href="javascript:;" id="select-all-records" >Select all <span id="total-records">' + selfObj.totalRecords + '</span> records.</a> <a href="javascript:;" id="clear-selection">Clear selection</a>';
                    $("#div-select-all-records").html(html);
                    $('#div-select-all-records').fadeIn('slow');
                }
                $('#records tbody tr').addClass("selected");
                selfObj.saveState(0, 'check_current', 1);
            } else if ($(this).prop('checked') == false) {
                selfObj.selectAllRecords = false;
                $('input[name=record_checkbox]').removeAttr('checked');
                $('input[name=record_checkbox]').prop('checked', false);
                $("#div-select-all-records").html('');
                $('#records tbody tr').removeClass("selected");
                selfObj.saveState(0, 'check_current', 0);
            }
        });
    }

    this.selectAllRecordsChk = function () {
        $(document).on('click', '#select-all-records', function () {
            selfObj.selectAllRecords = true;
            $(this).hide();
            $('input[name=record_checkbox]').attr('checked', 'checked');
            $('input[name=record_checkbox]').prop('checked', true);
            $('#records tbody tr').addClass("selected");
            selfObj.saveState(0, 'all', 1);
            $('#div-select-all-records').html('All <span id="records-on-page">' + selfObj.totalRecords + '</span> records selected. <a href="javascript:;" id="clear-selection">Clear selection</a>');
        });
    }

    this.clearSelection = function () {
        $(document).on('click', '#clear-selection', function () {
            selfObj.selectAllRecords = false;
            $('#selectAll').removeAttr('checked');
            $('input[name=record_checkbox]').removeAttr('checked');
            $('input[name=record_checkbox]').prop('checked', false);
            $('#div-select-all-records').fadeOut('slow');
            $('#records tbody tr').removeClass("selected");
            $("#selectedrecords").val('');
            $(this).hide();
            selfObj.saveState(0, 'all', 0);
        });
    }

    this.saveState = function (elementID, select, isChecked) {
        var id = '';
        var checked = 0;
        var isAll = 0;
        var selectedrecords = '';
        if (select) {
            if (select == 'all') {
                checked = isChecked;
                isAll = 1;
                selectedrecords = 'all';
            }
            else {
                checked = isChecked;
                if ($('input[name=record_checkbox]').attr("checked") == "checked") {
                    $('input[name=record_checkbox]').each(function () {
                        id += $(this).val() + ',';
                    });
                    selectedrecords = id;
                }
            }
        }
        else {
            id = elementID;
            if ($('#row_' + elementID).attr('checked') == 'checked' || $('#row_' + elementID).prop("checked") == true) {
                checked = 1;
                if (selectedrecords) {
                    selectedrecords = selectedrecords + ',' + id;
                } else {
                    selectedrecords = id;
                }
            } else {
                checked = 0;
            }
        }
        $("#selectedrecords").val(selectedrecords);
        $.ajax({
            type: 'POST',
            url: ajaxSaveStateUrl,
            data: {id: id, checked: checked, is_all: isAll, },
            dataType: 'json',
            success: function (response)
            {
                $("#selectedrecords").val(response.recordIds);
            }
        });
    };

    this.checkBoxes = function () {
        $(document).on('click', '.checkboxes', function () {
            selfObj.saveState($(this).val());
        });
    };

    this.exportRequest = function () {
        $('.export').click(function () {
            var checked = false;
            $('input[name=record_checkbox]').each(function () {
                if ($(this).prop("checked") == true) {
                    checked = true;
                }
            });
            if (checked) {
                var exportType = $(this).attr('data-type');
                $("#exportModal").modal('show');
                $("#exportType").val(exportType);
                $("#exportModal").show();
            } else {
                $.Dialog({
                    'title': 'Error',
                    'content': '<span style="font-size:13px;">Please select any record.</span>',
                    'draggable': false,
                    'overlay': true,
                    'closeButton': true,
                    'buttonsAlign': 'right',
                    shadow: true,
                    flat: true,
                    width: 400,
                    height: 150,
                    padding: 10,
                    'position': {
                        'zone': 'right'
                    },
                });
            }
        });
    }

    this.exportRecords = function () {
        $('#exportRequest').click(function () {
            var exportType = $("#exportType").val();
            var selectedrecords = $("#selectedrecords").val();
            if (exportType && selectedrecords) {
                $.ajax({
                    type: 'POST',
                    url: ajaxExportUrl,
                    data: {type: exportType, records: selectedrecords, merge: false},
                    dataType: 'json',
                    success: function (response)
                    {
                        $("#beforeExport").hide();
                        $("#afterExport").show();
                    }
                });
            } else {
                $("#exportModal").modal('hide');
                $.Dialog({
                    'title': 'Error',
                    'content': '<span style="font-size:13px;">Error occured. Please try again.</span>',
                    'draggable': false,
                    'overlay': true,
                    'closeButton': true,
                    'buttonsAlign': 'right',
                    shadow: true,
                    flat: true,
                    width: 400,
                    height: 150,
                    padding: 10,
                    'position': {
                        'zone': 'right'
                    },
                });
            }
        });
    }

    this.closeClicked = function () {
        $("#closeBtn, .closeBtn").on("click", function () {
            $('#selectAll').prop("checked", false);
            $('input[name=record_checkbox]').each(function () {
                $(this).prop("checked", false);
            });
            $('#records tr').each(function () {
                $(this).removeClass("selected");
            });
            $("#selectedrecords").val('');
            window.location.reload();
        });
    }

    this.exportMergeRequest = function () {
        $('.exportMerge').click(function () {
            var checked = false;
            $('input[name=record_checkbox]').each(function () {
                if ($(this).prop("checked") == true) {
                    checked = true;
                }
            });
            if (checked) {
                var exportType = $(this).attr('data-type');
                $("#exportMergeModal").modal('show');
                $("#beforeExportMerge").show();
                $("#modal-footer").show();
                $("#afterExportMerge").hide();
                $("#emfiletype").val(exportType);
                $("#emrecordIds").val($("#selectedrecords").val());
                $("#exportMergeModal").show();
            } else {
                $.Dialog({
                    'title': 'Error',
                    'content': '<span style="font-size:13px;">Please select any record.</span>',
                    'draggable': false,
                    'overlay': true,
                    'closeButton': true,
                    'buttonsAlign': 'right',
                    shadow: true,
                    flat: true,
                    width: 400,
                    height: 150,
                    padding: 10,
                    'position': {
                        'zone': 'right'
                    },
                });
            }
        });
    }
    this.showMergMsg = function () {
        var msg = '';
        if (successMsg) {
            msg = successMsg;
        } else if (errorMsg) {
            msg = '<span class="error">' + errorMsg + '</span>';
        }
        if (msg) {
            $("#beforeExportMerge").hide();
            $("#modal-footer").hide();
            $("#afterExportMerge").show();
            $("#afterExportMerge span").html(msg);
            $("#exportMergeModal").modal('show');
            $("#exportMergeModal").show();
        }
    }

    this.showMsg = function () {
        var msg = '';
        if (successMsg) {
            msg = successMsg;
        } else if (errorMsg) {
            msg = '<span class="error">' + errorMsg + '</span>';
        }
        if (msg) {
            $("#heading").html(popupHeading);
            $("#messageText span").html(msg);
            $("#messageText").show();
            $("#messageModal").modal('show');
            $("#messageModal").show();
        }
    }

    this.importRequest = function () {
        $('.import').click(function () {
            var importType = $(this).attr('data-type');
            $("#importModal").modal('show');
            $('#organization').chosen();
            $('#error_span').hide();
            $("#importModal #impfiletype").val(importType);
            $("#importModal").show();
        });
    }

    this.validateRecords = function () {
        $('#bulkEdit').click(function () {
            var selectedrecords = $("#selectedrecords").val();
            if (selectedrecords) {
                $('#bulk_edit_body').css('color', 'black');
                $('#bulk_edit_body').html('<img src="/images/ajax-loader.gif" /> <span><b>Processing please wait...</b></span>');
                $.ajax({
                    type: 'POST',
                    url: bulkUrl,
                    data: {records: selectedrecords},
                    dataType: 'json',
                    success: function (response)
                    {
                        if (!response.success) {
                            $('#bulk_edit_body').css('color', '#b94a48');
                            $("#bulk_edit_body").html('<b>' + response.msg + '</b>');
                            $("#bulkEditModal").modal('show');
                        } else {
                            $("#bulkEditModal").modal('show');
                            $(".bulkEditform").html(response.html);
                            $("#bulkEditModal").show();
                            $(".bulkEditform").show();
                            $("#bulk_process").hide();
                        }
                    }
                });
            } else {
                $('#bulk_edit_body').css('color', '#b94a48');
                $("#bulk_edit_body").html('<b>Select records to edit.</b>');
                $("#bulkEditModal").modal('show');
            }
        });
    }

    this.deleteRecords = function () {
        $('#deleteRecord').click(function () {
            var selectedrecords = $("#selectedrecords").val();
            if (selectedrecords) {
                $("#deleteModal").modal('show');
            }
        });
    }
}

