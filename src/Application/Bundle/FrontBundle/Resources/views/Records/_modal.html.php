<div id="exportModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true" style="display:none;">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header">
                <h4>Export Records</h4>
            </div>
            <div id="beforeExport">
                <div class="modal-body">
                    <p><span style="font-size:13px;">Are you sure you want to export the record(s)?</span></p>
                </div>
                <div class="modal-footer" id="modal-footer">
                    <button type="button" name="close" id="close" class="button closeModal" data-dismiss="modal">No</button> &nbsp;
                    <button type="button" name="yes" class="button primary" id="exportRequest">Yes</button>
                </div>
            </div>
            <div id="afterExport" style="display:none;">
                <div class="modal-body">
                    <p><span style="font-size:13px;">You will receive an email shortly with download link of exported records.</span></p>
                </div>
                <div class="modal-footer" id="modal-footer">
                    <button type="button" name="close" id="closeBtn" class="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="exportMergeModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exportMergeModalLabel" aria-hidden="true" style="display:none;">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header">
                <h4>Export and Merge Records</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo $view['router']->generate('record_export_merge') ?>" method="post" enctype="multipart/form-data" name="frmExportMerge">
                    <div id="beforeExportMerge">
                        <p><span style="font-size:13px;"></span></p>
                        <div class="pull-right">
                            <input type="file" name="mergetofile" class="required" required="required" /><br /><br />
                            <input type="hidden" name="emrecordIds" id="emrecordIds" value="" />
                            <input type="hidden" name="emfiletype" id="emfiletype" value="" />
                        </div>
                    </div>
                </form>
                <div id="afterExportMerge" style="display:none;">
                    <p><span style="font-size:13px;">

                        </span></p>
                    <div class="pull-right">
                        <button type="button" name="close" id="closeBtn" class="button" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="modal-footer">
                <button type="button" name="close" id="close" class="button closeModal" data-dismiss="modal">No</button> &nbsp;
                <button type="button" name="submit" id="submit" class="button primary" onclick="$('#exportMergeModal form').submit();">Submit</button>
            </div>
        </div>
    </div>
</div>

<div id="importModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true" style="display:none;">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <form action="<?php echo $view['router']->generate('import_records') ?>" method="post" enctype="multipart/form-data" name="frmExportMerge">

                <div class="modal-header">
                    <h4>Import Records</h4>
                </div>

                <div class="modal-body" style="height: 400px ! important; overflow: auto">
                    <div id="import_rec">
                        <span class="has-error text-danger" id="error_span" style='display:none'></span>
                        <p><span style="font-size:13px;">Are you sure you want to import the record(s)?</span></p>
                        <div class="pull-right">
                            <?php
                            if ($view['security']->isGranted('ROLE_SUPER_ADMIN')) {
                            ?>
                                <div>
                                    <select id='organization' name="organization">
                                        <option value=''>select organization</option>
                                        <?php
                                        foreach ($organizations as $organization) {
                                        ?>
                                            <option value="<?php echo $organization->getId(); ?>"><?php echo ucwords($organization->getName()); ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <br>
                                    <br>
                                </div>
                            <?php } ?>

                            <input type="file" name="importfile" class="required" required="required" id="importfile" /><br /><br />
                            <input type="hidden" name="impfiletype" id="impfiletype" value="" />
                        </div>
                    </div>
                    <div id="import_rec_1">
                        <input type="hidden" name="existingRecords" id="existingRecords" value="0" />
                    </div>

                </div>
                <div class="modal-footer" id="modal-footer_1">
                    <button type="button" name="close" id="close" class="button closeModal" data-dismiss="modal">No</button> &nbsp;
                    <?php
                    if ($view['security']->isGranted('ROLE_SUPER_ADMIN')) {
                    ?>
                        <button type="submit" name="submit" id="submit" class="button primary" onclick="return validateRecords(1);" value="0">Submit</button>
                    <?php
                    } else {
                    ?>
                        <button type="submit" name="submit" id="submit" class="button primary" onclick="return validateRecords(0);" value="0">Submit</button>
                    <?php } ?>
                </div>
                <div class="modal-footer" id="modal-footer_2" style="display: none;">
                    <button type="button" class="button closeModal" data-dismiss="modal" onclick="window.location.reload();">Cancel</button> &nbsp;
                    <button type="submit" name="" id="sub_1" class="button primary" onclick="addValue('sub_1', 1)" value="">Add New Records Only</button>
                    <button type="submit" name="" id="sub_2" class="button primary" onclick="addValue('sub_2', 2)" value="">Add New & Update Existing Records</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="messageModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true" style="display:none;">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header">
                <h4 id="heading"></h4>
            </div>
            <div class="modal-body">
                <div id="messageText" style="display:none;">
                    <p><span style="font-size:13px;">

                        </span>
                    </p>
                </div>
            </div>
            <div class="modal-footer" id="modal-footer">
                <button type="button" name="close" id="closeBtn" class="button closeBtn" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div id="bulkEditModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="bulkEditModalLabel" aria-hidden="true" style="display:none;">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Bulk Edit</h3>
            </div>
            <div id="bulk_process">
                <div class="modal-body" id="bulk_edit_body" style="font-size: 12px;">
                </div>
                <div class="modal-footer" id="bulk_edit_footer">
                    <button type="button" name="close" id="" class="button" data-dismiss="modal">Close</button>
                </div>
            </div>
            <div class="bulkEditform" style="display:none;">
            </div>
        </div>
    </div>
</div>

<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true" style="display:none;">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Delete Records</h3>
            </div>
            <div id="delete_process">
                <div class="modal-body" id="delete_body" style="font-size: 12px;">

                </div>
                <div class="modal-footer" id="delete_footer">
                    <button type="button" name="close" id="" class="button" data-dismiss="modal">No</button>
                    <button type="submit" id="delete_button" class="button primary" onclick="confirmDelete()">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($notification) { ?>
    <div id="notificationModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true" style="display:none;">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class="modal-header">
                    <h3 id="myModalLabel">Notification</h3>
                </div>
                <div id="notification_process">
                    <div class="modal-body" id="notification_body" style="font-size: 12px;">
                        You are not allowed to add more records. <?php if ($contact_person != "") { ?>Please contact <?php echo $contact_person; ?> to upgrade account.<?php } else { ?>Please upgrade your account.<?php } ?>
                    </div>
                    <div class="modal-footer" id="notification_footer">
                        <a class="" href="<?php echo $view['router']->generate('record_list') ?>"><input type="button" value="close"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<script>
    <?php if ($notification) { ?>
        $('#notificationModal').show();
        $('#notificationModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    <?php } ?>

    function validateRecords(org_test) {
        $('#error_span').hide();
        if (org_test == 1) {
            if ($('#organization').val() == '') {
                $('#error_span').html('Organization is required.')
                $('#error_span').show();
                return;
            }
        }
        var validate_url = "<?php echo $view['router']->generate('validate_records') ?>";
        var fd = new FormData();
        $('#importModal form').find('input').each(function() {
            if (this.type != "file") {
                fd.append(this.name, $(this).val());
            }
        });


        $('#importModal form').find('select').each(function() {
            if (this.type != "file") {
                fd.append(this.name, $(this).val());
            }
        });

        var file = $("#importfile");
        var individual_file = "";
        if (file.val() != "") {
            individual_file = file[0].files[0];
        }
        fd.append("importfile", individual_file);

        $.ajax({
            type: "POST",
            url: validate_url,
            data: fd,
            async: false,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {
                if (response.success == false) {
                    $('#error_span').html(response.message)
                    $('#error_span').show();
                    return false;
                } else if (response.message == "submit") {
                    return true;
                } else if (response.success == true) {
                    $("#existingRecords").val(response.count);
                    $('#import_rec').hide();
                    $('#import_rec_1').append("Unique Id(s) already existed in db: <br>" + response.message);
                    $("#modal-footer_1").remove();
                    $("#modal-footer_2").show();
                    return false;
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                $('#error_span').html(xhr.statusText);
                $('#error_span').show();
                return false;
            }
        });
    }

    function submitImportForm() {
        console.log("submittinggg...");
        $('#importModal form').submit();
    }

    function checkOrganization() {
        if ($('#organization').val() == '') {
            $('#error_span').show();
        } else {
            $('#importModal form').submit();
        }
    }

    function confirmDelete() {
        var selected = $("#selectedrecords").val();
        if (selected) {
            $("#deleteModal").hide('show');
            $.blockUI();
            $.ajax({
                type: 'POST',
                url: delUrl,
                data: {
                    records: selected
                },
                dataType: 'json',
                success: function(response) {
                    //$.unblockUI();
                    window.location.reload();
                }
            });
        }
    }

    function addValue(ele, val) {
        $("#" + ele).attr("name", "submit");
        $("#" + ele).val(val);
    }
</script>
