function initialize_records_form() {
    $('#diskDiameters_lbl').hide();
    $('#reelDiameters_lbl').hide();
    $('#mediaDiameters_lbl').hide();
    $('#tapeThickness_lbl').hide();
    $('#trackTypes_lbl').hide();
    $('#cassetteSize_lbl').hide();
    $("#formatVersion_lbl").hide();
    if (selectedMediaType)
        $('.new #mediaType option[value="' + selectedMediaType + '"]').attr("selected", "selected");
    $.mask.definitions['y'] = '[1-2,x]';
    $.mask.definitions['m'] = '[0-1,x]';
    $.mask.definitions['d'] = '[0-3,x]';
    $.mask.definitions['g'] = '[0-9,x]';
    $("#creationDate, #contentDate").mask("yggg-mg-dg", {optional: true});
    updateFormat();
    onChangeMediaType();
    showUpdateFields();
    saveBulkEdit();
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
            $('#slides_lbl, #monoStereo_lbl, #noiceReduction_lbl').hide();
        } else {
            $('#slides_lbl, #monoStereo_lbl, #noiceReduction_lbl').show();
        }
        if ($(this).val()) {
            /// call to get base dropdown options
            $.ajax({
                type: "GET",
                url: baseUrl + 'getBase/' + $('#format').val(),
                success: function (response) {
                    if (response != "") {
                        $("#bases_lbl").show();
                        $("#bases").html(response);
                    } else {
                        $("#bases_lbl").hide();
                    }
                }

            }); // Ajax Call
            /// call to get reel diameters dropdown options
            $.ajax({
                type: "GET",
                url: baseUrl + 'getReelDiameter/' + $(this).val() + '/' + $("#mediaType").val(),
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
                $.ajax({
                    type: "GET",
                    url: baseUrl + 'getRecordingSpeed/' + $(this).val() + '/' + $("#mediaType").val(),
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
            $.ajax({
                type: "GET",
                url: baseUrl + 'getFormatVersion/' + $(this).val(),
                success: function (response) {
                    if (response != "") {
                        $("#formatVersion_lbl").show();
                        $("#formatVersion").html(response);
                    } else {
                        $("#formatVersion_lbl").hide();
                    }
                }

            }); // Ajax Call   
        }
    }).change();

}

function onChangeMediaType() {
    $(".new #mediaType").change(function () {
        console.log($(this).val());
        if ($(this).val() == 3) {
            window.location.href = baseUrl + 'video/new';
        } else if ($(this).val() == 2) {
            window.location.href = baseUrl + 'film/new';
            ;
        } else if ($(this).val() == 1) {
            window.location.href = baseUrl + 'audio/new';
            ;
        }
    });
}

function saveBulkEdit() {
    $("#submitBulkEdit").click(function () {
        data = $('#frmBulkEdit').serialize();
        $.ajax({
            type: "POST",
            url: bulkEdit,
            data: data,
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    console.log(response.success);
                    window.location.reload();
                } 
            }
        }); // Ajax Call  
    });
}