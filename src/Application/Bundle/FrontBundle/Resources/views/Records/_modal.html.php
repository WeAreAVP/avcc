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
    <div class="modal-body">
        <form action="<?php echo $view['router']->generate('record_export_merge') ?>" method="post" enctype="multipart/form-data" name="frmExportMerge"> 
            <div id="beforeExportMerge">
                <p><span style="font-size:13px;">Are you sure you want to export and merge the record(s)?</span></p>
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
