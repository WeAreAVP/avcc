function initialize_records_form() {
    $('#format_lbl').hide();
    $('#diskDiameters_lbl').hide();
    $('#reelDiameters_lbl').hide();
    $('#mediaDiameters_lbl').hide();
    $('#tapeThickness_lbl').hide();
    $('#trackTypes_lbl').hide();
    $('#cassetteSize_lbl').hide();
    $("#formatVersion_lbl").hide();
    $('#bases_lbl').hide();
    $('#recordingSpeed_lbl').hide();

    if (selectedMediaType)
        $('.new #mediaType option[value="' + selectedMediaType + '"]').attr("selected", "selected");
    $.mask.definitions['y'] = '[1-2,x]';
    $.mask.definitions['m'] = '[0-1,x]';
    $.mask.definitions['d'] = '[0-3,x]';
    $.mask.definitions['g'] = '[0-9,x]';
    $("#creationDate, #contentDate").mask("yggg-mg-dg", {optional: true});
    updateProjects();
    updateFormat();
    onChangeMediaType();
    showUpdateFields();
    saveBulkEdit();
    closeBtn();
    uniqueIdCheck();
    $("input,textarea,select").keypress(function () {
        changes = true;
    });
    $("select").click(function () {
        if ($(this).attr('id') != 'mediaType') {
            changes = true;
        } else {
            chages = false;
        }
    });

    window.onbeforeunload = function () {
        if (changes)
        {
            return 'The changes are not saved and will be lost.';
        }
    }
    $('form').submit(function () {
        changes = false;
        return true; // return false to cancel form action
    });
}

function uniqueIdCheck() {
    $('#uniqueId').blur(function () {
        $.ajax({
            type: "POST",
            url: 'http://avccqa.avpreserve.com/records/checkid' ,
            dataType: 'json',
            data: { unique_id:  $('#uniqueId').val()},
            success: function (response) {
                console.log('almost done wd every thng....');
            }
        });
    });
}
function updateFormat() {
    var selfObj = this;
    selfObj.ajaxCall = false;
    /// call to get base dropdown options
//    $('#processing').html('<img src="/images/ajax-loader.gif" /> <span><b>Processing please wait...</b></span>');
    if (selectedFormat) {
        url = baseUrl + 'getFormat/' + $("#mediaType").val() + '/' + selectedFormat;
    } else {
        url = baseUrl + 'getFormat/' + $("#mediaType").val();
    }
    $.ajax({
        type: "GET",
        url: url,
        success: function (response) {
            if (response != "") {
                $('#format_lbl').show();
                $("#format").html(response);
                $("#format").change();
                $('#processing').hide();
                $('#fieldsPanel').show();
            }
        }

    }); // Ajax Call 
}

function showUpdateFields() {
    $('#format').change(function () {
        var showDiskDiameter = [16, 17, 18, 19, 20, 28];
        var showMediaDiameter = [1, 2, 3, 4, 5, 24];
        var showTapeThickness = [1, 2, 3, 4, 5];
        var showTrackType = [1, 2, 3, 4, 5];
        var showCassetteSize = [59, 60, 33, 34, 35, 43, 44, 46, 47, 48, 52, 53, 54, 55, 57];
        var hideRecordingSpeedFormat = [37, 39, 40, 41];
        var hideIfFormat = [24, 25, 26];
        var showSideIfFormat = [1, 10, 11, 13, 14, 16, 17, 18, 19, 20, 27, 28];
        var showNoiceRedIfFormat = [1, 2, 4, 5, 6, 7, 10, 13, 14, 27];
        var hideBaseIfFormat = [3, 4, 5];

        if (jQuery.inArray(parseInt($(this).val()), showDiskDiameter) >= 0) {
            $('#diskDiameters_lbl').show();
        } else {
            $('#diskDiameters_lbl').hide();
        }

        if (jQuery.inArray(parseInt($(this).val()), showMediaDiameter) >= 0) {
            $('#mediaDiameters_lbl').show();
        } else {
            $('#mediaDiameters_lbl').hide();
        }

        if (jQuery.inArray(parseInt($(this).val()), showTapeThickness) >= 0) {
            $('#tapeThickness_lbl').show();
        } else {
            $('#tapeThickness_lbl').hide();
        }

        if (jQuery.inArray(parseInt($(this).val()), showTrackType) >= 0) {
            $('#trackTypes_lbl').show();
        } else {
            $('#trackTypes_lbl').hide();
        }

        if (jQuery.inArray(parseInt($(this).val()), showCassetteSize) >= 0) {
            $('#cassetteSize_lbl').show();
        } else {
            $('#cassetteSize_lbl').hide();
        }

        if (jQuery.inArray(parseInt($(this).val()), hideIfFormat) >= 0) {
            $('#slides_lbl, #monoStereo_lbl').hide();
        } else {
            $('#slides_lbl, #monoStereo_lbl').show();
        }
        if (jQuery.inArray(parseInt($(this).val()), showSideIfFormat) >= 0) {
            $('#slides_lbl').show();
        } else {
            $('#slides_lbl').hide();
        }
        if (jQuery.inArray(parseInt($(this).val()), showNoiceRedIfFormat) >= 0) {
            $('#noiceReduction_lbl').show();
        } else {
            $('#noiceReduction_lbl').hide();
        }
        if ($(this).val()) {
            if (jQuery.inArray(parseInt($(this).val()), hideBaseIfFormat) >= 0) {
                $("#bases_lbl").hide();
            } else {
                /// call to get base dropdown options
                $.ajax({
                    type: "GET",
                    url: baseUrl + 'getBase/' + $(this).val(),
                    success: function (response) {
                        if (response != "") {
                            $("#bases_lbl").show();
                            $("#bases").html(response);
                        } else {
                            $("#bases_lbl").hide();
                        }
                    }

                }); // Ajax Call
            }
            /// call to get reel diameters dropdown options
            if (selectedRD) {
                RDurl = baseUrl + 'getReelDiameter/' + $(this).val() + '/' + $("#mediaType").val() + '/' + selectedRD;
            } else {
                RDurl = baseUrl + 'getReelDiameter/' + $(this).val() + '/' + $("#mediaType").val();
            }
            $.ajax({
                type: "GET",
                url: RDurl,
                success: function (response) {
                    if (response != "") {
                        $("#reelDiameters_lbl").show();
                        $("#reelDiameters").html(response);
                    } else {
                        $("#reelDiameters_lbl").hide();
                    }
                }

            }); // Ajax Call 
            if (jQuery.inArray(parseInt($(this).val()), hideRecordingSpeedFormat) >= 0) {
                $('#recordingSpeed_lbl').hide();
            } else {
                $('#recordingSpeed_lbl').show();
                /// call to get recording speed dropdown options
                if (selectedRS) {
                    url = baseUrl + 'getRecordingSpeed/' + $(this).val() + '/' + $("#mediaType").val() + '/' + selectedRS;
                } else {
                    url = baseUrl + 'getRecordingSpeed/' + $(this).val() + '/' + $("#mediaType").val();
                }
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function (response) {
                        if (response != "") {
                            $("#recordingSpeed_lbl").show();
                            $("#recordingSpeed").html(response);
                        } else {
                            $("#recordingSpeed_lbl").hide();
                        }
                    }
                }); // Ajax Call  
            }
            /// call to get formatversion dropdown options
            if (selectedFormatVersion) {
                formatVersiourl = baseUrl + 'getFormatVersion/' + $(this).val() + '/' + selectedFormatVersion;
            } else {
                formatVersiourl = baseUrl + 'getFormatVersion/' + $(this).val();
            }
            $.ajax({
                type: "GET",
                url: formatVersiourl,
                success: function (response) {
                    if (response != "") {
                        $("#formatVersion_lbl").show();
                        $("#formatVersion").html(response);
                    } else {
                        $("#formatVersion_lbl").hide();
                    }
                }

            }); // Ajax Call   
        } else {
            $('#diskDiameters_lbl').hide();
            $('#reelDiameters_lbl').hide();
            $('#mediaDiameters_lbl').hide();
            $('#tapeThickness_lbl').hide();
            $('#trackTypes_lbl').hide();
            $('#cassetteSize_lbl').hide();
            $("#formatVersion_lbl").hide();
            $('#bases_lbl').hide();
            $('#recordingSpeed_lbl').hide();
        }
    });

}

function onChangeMediaType() {
    $(".new #mediaType").change(function () {
        if ($(this).val() == 3) {
            $('#fieldsPanel').hide();
            $('#processing').show();
            window.location.href = baseUrl + 'video/new';
        } else if ($(this).val() == 2) {
            $('#fieldsPanel').hide();
            $('#processing').show();
            window.location.href = baseUrl + 'film/new';
        } else if ($(this).val() == 1) {
            $('#fieldsPanel').hide();
            $('#processing').show();
            window.location.href = baseUrl + 'audio/new';
        }
    });
}

function saveBulkEdit() {
    $("#submitBulkEdit").click(function () {
        data = $('#frmBulkEdit').serialize();
        $(document).ajaxStart(function () {
            $("#frmBulkEdit").hide();
            $('#editProcessing').show();
            $('#editProcessing').css('color', 'black');
            $('#editProcessing').html('<img src="/images/ajax-loader.gif" /> <span><b>Processing please wait...</b></span>');
        });
        $.ajax({
            type: "POST",
            url: bulkEdit,
            data: data,
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    window.location.reload();
                }
            }
        }); // Ajax Call  
    });
}

function closeBtn() {
    $(".bulkEditCloseBtn").on('click', function () {
        $.modal.close();
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

function updateProjects() {
    if (selectedProject) {
        urlProjects = baseUrl + 'getAllProjects/' + selectedProject;
    } else {
        urlProjects = baseUrl + 'getAllProjects';
    }
    $.ajax({
        type: "GET",
        url: urlProjects,
        success: function (response) {
            if (response != "") {
                $("#project").html(response);
            }
        }
    }); // Ajax Call     
}