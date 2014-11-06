function initialize_records_form() {
    $('#diskDiameters_lbl').hide();
    $('#reelDiameters_lbl').hide();
    $('#mediaDiameters_lbl').hide();
    $('#tapeThickness_lbl').hide();
    $('#trackTypes_lbl').hide();
    showUpdateFields();
    $.mask.definitions['y'] = '[1-2,x]';
    $.mask.definitions['m'] = '[0-1,x]';
    $.mask.definitions['d'] = '[0-3,x]';
    $.mask.definitions['g'] = '[0-9,x]';
    $("#creationDate, #contentDate").mask("yggg-mg-dg", {optional: true});

    console.log($("#mediaType").val());
    updateFormat();
}
function updateFormat() {
    /// call to get base dropdown options
    $.ajax({
        type: "GET",
        url: baseUrl + 'getFormat/' + $("#mediaType").val(),
        success: function (response) {
            if (response != "") {
                $("#format").html(response);
            } else {                
            }
        }

    }); // Ajax Call
}

function showUpdateFields() {
    $('#format').change(function () {
        var showDiskDiameter = [16, 17, 18, 19, 20, 28];
        var showReelDiameter = [1, 2, 3, 4, 5, 24];
        var showMediaDiameter = [1, 2, 3, 4, 5, 24];
        var showTapeThickness = [1, 2, 3, 4, 5];
        var showTrackType = [1, 2, 3, 4, 5];

        if (jQuery.inArray(parseInt($(this).val()), showDiskDiameter) >= 0) {
            $('#diskDiameters_lbl').show();
        } else {
            $('#diskDiameters_lbl').hide();
        }

        if (jQuery.inArray(parseInt($(this).val()), showReelDiameter) >= 0) {
            $('#reelDiameters_lbl').show();
        } else {
            $('#reelDiameters_lbl').hide();
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
        /// call to get recording speed dropdown options
        $.ajax({
            type: "GET",
            url: baseUrl + 'getRecordingSpeed/' + $(this).val(),
            success: function (response) {
                if (response != "") {
                    $("#recordingSpeed_lbl").show();
                    $("#recordingSpeed").html(response);
                } else {
                    $("#recordingSpeed_lbl").hide();
                }
            }

        }); // Ajax Call
    });

}
    