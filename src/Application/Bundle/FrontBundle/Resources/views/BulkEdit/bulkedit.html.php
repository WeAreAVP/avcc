<div id="bulkEdit" class="">
    <form method="post" name="frmBulkEdit" action="" id="frmBulkEdit">
        <input type="hidden" name="records" id="records" value="<?php echo $selectedrecords; ?>"/>
        <?php $isMediaDisable = $disableFields['mediaType']; ?>
        <?php $isFormatDisable = $disableFields['format']; ?>
        <input type="hidden" name="mediaDisable" id="mediaDisable" value="<?php echo $isMediaDisable; ?>"/>
        <input type="hidden" name="formatDisable" id="mediaDisable" value="<?php echo $isFormatDisable; ?>"/>
        <input type="hidden" name="mediaTypeId" id="mediaTypeId" value="<?php echo $mediaTypeId; ?>"/>
        <div id="bulk_process">
            <div class="modal-body" id="bulk_edit_body" style="font-size: 12px;">
                <fieldset>
                    <div id="mediatype_lbl" class="col-lg-6" style="">
                        <label for="mediaType" class="required"> </label>
                        <div data-role="input-control" class="input-control">
                            <?php // $selected?>
                            <select required="required" class="size4 disabled" name="mediaType" id="mediaType" disabled="disabled">
                                <?php foreach ($relatedFields['mediaTypes'] as $mediaType) { ?>
                                    <option value="<?php echo $mediaType->getId(); ?>"><?php echo $mediaType->getName() ?></option>
                                <?php } ?>
                            </select> 
                        </div>
                    </div>
                    <div id="format_lbl" class="col-lg-6" style="">
                        <label for="format" class="required"> </label>Format
                        <div data-role="input-control" class="input-control">
                            <select required="required" class="size4" name="format" id="format" <?php echo ($isMediaDisable) ? 'disabled="disabled"' : ''; ?>>
                                <option value=""></option>                                    
                            </select> 
                        </div>
                    </div>
                    <div id="project_lbl" class="col-lg-6" style="">
                        <label for="project" class="required"> </label>Project Name
                        <div data-role="input-control" class="input-control new">
                            <select required="required" class="size4" name="project" id="project">
                                <option value=""></option>
                                <?php foreach ($relatedFields['projects'] as $project) { ?>
                                    <option value="<?php echo $project->getId(); ?>"><?php echo $project->getName() ?></option>
                                <?php } ?>
                            </select>                                                       
                        </div>
                    </div>
                    <div id="location_lbl" class="col-lg-6" style="">
                        <label for="location" class="required"> </label>Location 
                        <div data-role="input-control" class="input-control new">
                            <input type="text" class="size4" required="required" name="location" id="location">                                
                        </div>
                    </div>
                    <div id="title_lbl" class="col-lg-6" style="">
                        <label for="title" class="required"> </label>Title
                        <div data-role="input-control" class="input-control">
                            <input type="text" class="size4" required="required" name="title" id="title">                                
                        </div>
                    </div>
                    <div id="collectionName_lbl" class="col-lg-6" style="">
                        <label for="collectionName" class="required"> </label>Collection Name
                        <div data-role="input-control" class="input-control">
                            <input type="text" class="size4" required="required" name="collectionName" id="collectionName">                                
                        </div>
                    </div>
                    <div id="description_lbl" class="col-lg-6" style="">
                        <label for="description" class="required"> </label>Description
                        <div data-role="input-control" class="input-control">
                            <input type="text" class="size4" required="required" name="description" id="description">                                
                        </div>
                    </div>
                    <div id="commercial_lbl" class="col-lg-6" style="">
                        <label for="commercial"> </label>Commercial
                        <div data-role="input-control" class="input-control new">
                            <select class="size4" name="commercial" id="commercial">
                                <option value=""></option>
                                <?php foreach ($relatedFields['commercial'] as $commercial) { ?>
                                    <option value="<?php echo $commercial->getId(); ?>"><?php echo $commercial->getName() ?></option>
                                <?php } ?>
                            </select>                                                       
                        </div>
                    </div>
                    <div id="contentDuration_lbl" class="col-lg-6" style="">
                        <label for="contentDuration"> </label>Content Duration 
                        <div data-role="input-control" class="input-control text">
                            <input type="text" class="size4" name="contentDuration" id="contentDuration">
                        </div>
                    </div>
                </fieldset>
            </div><br />
            <div class="modal-footer" id="bulk_edit_footer">
                <button type="button" name="close" id="closeBtn" class="button closeBtn simplemodal-close">Close</button>
                <button type="button" name="submit" id="submitBulkEdit" class="button primary">Submit</button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    var baseUrl = '<?php echo $view['router']->generate('record') ?>';
    var selectedFormat = '';
    var selectedMediaType = '';
    var bulkEdit = '<?php echo $view['router']->generate('bulkedit_edit') ?>';
    $(document).ready(function () {
        initialize_records_form();
    });
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
        showUpdateFields();

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

    function saveBulkEdit() {
        $("#submitBulkEdit").click(function () {
            data = $('#frmBulkEdit').serialize();
            $.ajax({
                type: "POST",
                url: baseUrl + bulkEdit,
                data: data,
                dataType: 'json',
                success: function (response) {
                    if (response.success == 'true') {
                        window.location.reload();
                    }
                }
            }); // Ajax Call  
        });
    }
</script>

