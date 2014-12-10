<div id="bulkEditModal" class="" tabindex="-1" role="dialog" style="display:none;">
    <form method="post" name="frmBulkEdit" action="">
        <input type="hidden" name="records" id="records" value="<?php echo $selectedrecords; ?>"/>
        <h4 id="heading">Bulk Edit</h4>
        <div id="bulk_process">
            <div class="modal-body" id="bulk_edit_body" style="font-size: 12px;">

            </div><br />
            <div class="modal-footer" id="bulk_edit_footer">
                <button type="button" name="close" id="closeBtn" class="button closeBtn simplemodal-close">Close</button>
            </div>
        </div>
    </form>
</div>

