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
<script src="<?php echo $view['assets']->getUrl('js/manage.records.js') ?>"></script>
<script type="text/javascript">
    var baseUrl = '<?php echo $view['router']->generate('record') ?>';
    var selectedFormat = '';
    var selectedMediaType = '';
    var bulkEdit = '<?php echo $view['router']->generate('bulkedit_edit') ?>';
    $(document).ready(function () {
        initialize_records_form();
    });
</script>

