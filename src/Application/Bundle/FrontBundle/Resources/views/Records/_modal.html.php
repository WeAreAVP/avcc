<div id="exportModal" class="" tabindex="-1" role="dialog" style="display:none;">
    <h4>Export Records</h4>
    <div id="beforeExport">
        <p><span style="font-size:13px;">Are you sure you want to export the record(s)?</span></p>
        <div class="pull-right">
            <button type="button" name="close" id="close" class="button closeModal simplemodal-close">No</button> &nbsp;
            <button type="button" name="yes" class="button primary" id="exportRequest">Yes</button>
        </div>
    </div>
    <div id="afterExport" style="display:none;">
        <p><span style="font-size:13px;">You will receive an email shortly with download link of exported records.</span></p>
        <div class="pull-right">
            <button type="button" name="close" id="closeBtn" class="button simplemodal-close">Close</button>
        </div>
    </div>
</div>

<div id="exportMergeModal" class="" tabindex="-1" role="dialog" style="display:none;">
    <h4>Export and Merge Records</h4>
    <br />
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
                <button type="button" name="close" id="closeBtn" class="button simplemodal-close">Close</button>
            </div>
        </div>
    </div>
    <div class="modal-footer" id="modal-footer">
        <button type="button" name="close" id="close" class="button closeModal simplemodal-close">No</button> &nbsp;
        <button type="button" name="submit" id="submit" class="button primary" onclick="$('#exportMergeModal form').submit();">Submit</button>
    </div>
</div>

<div id="importModal" class="" tabindex="-1" role="dialog" style="display:none;">
    <h4>Import Records</h4>
    <div class="modal-body">
        <form action="<?php echo $view['router']->generate('import_records') ?>" method="post" enctype="multipart/form-data" name="frmExportMerge"> 
            <div id="beforeExportMerge">
                <p><span style="font-size:13px;">Are you sure you want to import the record(s)?</span></p>
                <div class="pull-right">
                    <input type="file" name="importfile" class="required" required="required" /><br /><br />
                    <input type="hidden" name="impfiletype"  id="impfiletype" value="" />
                </div>
            </div>
        </form>                    
    </div>
    <div class="modal-footer" id="modal-footer">
        <button type="button" name="close" id="close" class="button closeModal simplemodal-close">No</button> &nbsp; 
        <button type="button" name="submit" id="submit" class="button primary" onclick="$('#importModal form').submit();">Submit</button>
    </div>
</div>
<div id="messageModal" class="" tabindex="-1" role="dialog" style="display:none;">
    <h4 id="heading"></h4>
    <div class="modal-body">         
        <div id="messageText" style="display:none;">
            <p><span style="font-size:13px;">

                </span>
            </p>             
        </div>            
    </div>
    <div class="modal-footer" id="modal-footer">
        <button type="button" name="close" id="closeBtn" class="button closeBtn simplemodal-close">Close</button>
    </div>
</div>
<div id="bulkEditModal1" class="mCustomScrollbar" tabindex="-1" role="dialog" style="display:none;height:500px">
    <h4 id="heading">Bulk Edit</h4>
    <div id="bulk_process">
        <div class="modal-body" id="bulk_edit_body" style="font-size: 12px;">

        </div><br />
        <div class="modal-footer" id="bulk_edit_footer">
            <button type="button" name="close" id="" class="button simplemodal-close">Close</button>
        </div>
    </div>
    <div class="bulkEditform" style="display:none;"></div>
</div>
<div id="bulkEditModal" class="modal" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Bulk Edit</h3>
            </div>
            <div class="modal-body">
                <div id="bulk_edit_body"></div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true" id="">CLose</button>

            </div>
        </div>
    </div> 
</div>