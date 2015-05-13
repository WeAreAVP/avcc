<?php $view->extend('FOSUserBundle::layout.html.php') ?>
<?php $view['slots']->start('body') ?>

<div class="grid fluid">
    <h1>
        <a href="<?php echo $view['router']->generate('record_list') ?>"><i class="icon-arrow-left-3 smaller"></i> </a>
        New Record - <?php echo ucwords($type) ?>
    </h1>
   
    <div id="fieldsPanel" style="display:none;">
      
                
                    <label>Media Type</label>
                    
                    
                </div>
        <a href="<?php echo $view['router']->generate('record_list') ?>" name="cancle" class="button">Cancel</a>&nbsp;
       
    </div>
</div>
<script src="<?php echo $view['assets']->getUrl('js/manage.records.js') ?>"></script>
<script type="text/javascript">
    var changes = false;
    var baseUrl = '<?php echo $view['router']->generate('record') ?>';
    var selectedFormat = '<?php echo ($entity->getRecord() && $entity->getRecord()->getFormat()) ? $entity->getRecord()->getFormat()->getId() : ''; ?>';
    var selectedMediaType = 2;
    var selectedFormatVersion = '';
    var selectedRS = '';
    var selectedRD = '<?php echo ($entity->getRecord() && $entity->getRecord()->getReelDiameters()) ? $entity->getRecord()->getReelDiameters()->getId() : ''; ?>';
    var selectedProject = '<?php echo ($entity->getRecord() && $entity->getRecord()->getProject()) ? $entity->getRecord()->getProject()->getId() : ''; ?>';
    var viewUrl = baseUrl + 'film/new/';
    var projectId = <?php echo ($app->getSession()->get('filmProjectId')) ? $app->getSession()->get('filmProjectId') : 0 ?>;
    $(document).ready(function () {
        initialize_records_form();
        $('#mediaType,#project,#format').change(function(){
        if ($('#mediaType').val() == '' || $('#project').val() == '' || $('#format').val() == '') {
            $('#collectionName_lbl').hide();
            $('#description_lbl').hide();
            $('#commercial_lbl').hide();
            $('#footage_lbl').hide();
            $('#reelCore_lbl').hide();
            $('#reelDiameters_lbl').hide();
            $('#diskDiameters_lbl').hide();
            $('#colors_lbl').hide();
            $('#sound_lbl').hide();
            $('#frameRate_lbl').hide();
            $('#acidDetectionStrip_lbl').hide();
            $('#shrinkage_lbl').hide();
            $('#contentDuration_lbl').hide();
            $('#mediaDuration_lbl').hide();
            $('#creationDate_lbl').hide();
            $('#contentDate_lbl').hide();
            $('#isReview_lbl').hide();
            $('#genreTerms_lbl').hide();
            $('#contributor_lbl').hide();
            $('#generation_lbl').hide();
            $('#part_lbl').hide();
            $('#copyrightRestrictions_lbl').hide();
            $('#duplicatesDerivatives_lbl').hide();
            $('#relatedMaterial_lbl').hide();
            $('#conditionNote_lbl').hide();
        }else{
            showUpdateFields();
            $('#footage_lbl').show();
            $('#reelCore_lbl').show();
            $('#colors_lbl').show();
            $('#sound_lbl').show();
            $('#frameRate_lbl').show();
            $('#acidDetectionStrip_lbl').show();
            $('#shrinkage_lbl').show();
            $('#collectionName_lbl').show();
            $('#description_lbl').show();
            $('#commercial_lbl').show();
            $('#contentDuration_lbl').show();
            $('#mediaDuration_lbl').show();
            $('#creationDate_lbl').show();
            $('#contentDate_lbl').show();
            $('#isReview_lbl').show();
            $('#genreTerms_lbl').show();
            $('#contributor_lbl').show();
            $('#generation_lbl').show();
            $('#part_lbl').show();
            $('#copyrightRestrictions_lbl').show();
            $('#duplicatesDerivatives_lbl').show();
            $('#relatedMaterial_lbl').show();
            $('#conditionNote_lbl').show();
        }
    });
    });
</script>
<?php
$view['slots']->stop();
?>
