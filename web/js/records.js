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
//			$('#container').removeClass('container');
//			$('#container').css('margin', '20px');
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
        selfObj.addKeyword();
        selfObj.removeFilter();
        selfObj.removeKeywordFilter();
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
                selfObj.initDataTable();
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
            $('#limit_field_text').html(customFieldName);
        });
    }

    this.addKeyword = function () {
        checkEvent = false;
        $('#addKeyword').click(function () {
            checkEvent = true;
        });
        $('#keywordSearch').keypress(function (e) {
            if (e.which == '13') {
                checkEvent = true;
            }
        });
        console.log(checkEvent);
        if (checkEvent==true && $('#keywordSearch').val() != '') {
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
}




