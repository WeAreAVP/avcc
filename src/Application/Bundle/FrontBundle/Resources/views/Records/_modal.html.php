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
                            <input type="hidden" name="emfiletype"  id="emfiletype" value="" />
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
            <div class="modal-header">
                <h4>Import Records</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo $view['router']->generate('import_records') ?>" method="post" enctype="multipart/form-data" name="frmExportMerge">
                    <div id="beforeExportMerge">
                        <p><span style="font-size:13px;">Are you sure you want to import the record(s)?</span></p>
                        <div class="pull-right">
                            <?php //if (isGranted('ROLE_SUPER_ADMIN')){ ?>
                            <select name="organization">
                                <option value=''>select organization</option>
                                <?php
                                foreach ($organizations as $organization) {
                                    ?>
                                    <option <?php echo $selected; ?> value="<?php echo $organization->id; ?>"><?php echo ucwords($organization->name); ?></option>
                                    <?php
                                }
                                ?>   
                            </select>
                            <?php// }?>
                            <input type="file" name="importfile" class="required" required="required" /><br /><br />
                            <input type="hidden" name="impfiletype"  id="impfiletype" value="" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="modal-footer">
                <button type="button" name="close" id="close" class="button closeModal" data-dismiss="modal">No</button> &nbsp;
                <button type="button" name="submit" id="submit" class="button primary" onclick="$('#importModal form').submit();">Submit</button>
            </div>
        </div>
    </div>
</div>
<div id="messageModal"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true" style="display:none;">
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
