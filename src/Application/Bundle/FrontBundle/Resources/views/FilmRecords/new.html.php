<?php $view->extend('FOSUserBundle::layout.html.php') ?>
<?php $view['slots']->start('body') ?>

<div class="grid fluid">
    <h1>
        <a href="<?php echo $view['router']->generate('record_list') ?>"><i class="icon-arrow-left-3 smaller"></i> </a>
        New Record - <?php echo ucwords($type) ?>
    </h1>
    <div id="processing">
        <span><b>Processing please wait...</b></span>
    </div>
    <div id="fieldsPanel" style="display:none;">
        <?php echo $view['form']->start($form) ?>
        <?php echo $view['form']->errors($form) ?>
        <fieldset>
            <?php echo $view['form']->errors($form) ?>
            <?php // echo $view['form']->widget($form) ?>
            <?php foreach ($fieldSettings[strtolower($type)] as $filmField): ?>
                <?php
                $field = explode('.', $filmField['field']);
                if (count($field) == 2) {
                    $index = $field[1];
                } else {
                    $index = $field[0];
                }
                $_style = (count($field) == 2 && $field[1] == 'isReview') ? 'width: 180px;float: left;margin-bottom: 15px;' : 'width: 200px';
                ?>
                <div style="<?php echo ($filmField['hidden']) ? 'display:none;' : ''; ?>" class="col-lg-6" id="<?php echo (count($field) == 2) ? $field[1] . '_lbl' : $field[0] . '_lbl' ?>">
                    <div class="label_class" data-toggle="popover" data-placement="bottom" data-content="<?php echo isset($tooltip[$index]) ? $tooltip[$index] : ''; ?>" style="<?php echo  $_style ?>">                   
                        <?php
                        $attr = ($filmField['is_required']) ? array('class' => 'size4') : array('class' => 'size4');
                        echo $view['form']->label((count($field) == 2) ? $form[$field[0]][$field[1]] : $form[$field[0]], ' ');
                        echo $filmField['title'];
                        echo ($filmField['is_required']) ? "&nbsp;<span>*</span>" : "";
                        ?>
                    </div>
                    <?php
                    if (count($field) == 2 && $field[1] == 'isReview')
                        $style = '';
                    else if (count($field) == 2 && $field[1] == 'conditionNote' || count($field) == 2 && $field[1] == 'generalNote')
                         $style = 'textarea';
                    else
                        $style = 'text';
                    ?>
                    <div class="input-control <?php echo $style; ?> new" data-role="input-control">
                         <?php 
                        $_attr = (count($field) == 2 && $field[1] == 'isReview') ? array() : $attr;
                        ?>
                        <?php echo $view['form']->widget((count($field) == 2) ? $form[$field[0]][$field[1]] : $form[$field[0]], array('id' => (count($field) == 2) ? $field[1] : $field[0], 'attr' => $_attr)) ?>
                        <span class="has-error text-danger"><?php echo $view['form']->errors((count($field) == 2) ? $form[$field[0]][$field[1]] : $form[$field[0]]) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </fieldset><br />
        <?php echo $view['form']->widget($form['record']['userId']) ?>
        <a href="<?php echo $view['router']->generate('record_list') ?>" name="cancle" class="button">Cancel</a>&nbsp;
        <?php echo $view['form']->widget($form['submit'], array('attr' => array('class' => 'button primary'))) ?>&nbsp;
        <?php echo $view['form']->widget($form['save_and_new']) ?>&nbsp;
        <?php echo $view['form']->widget($form['save_and_duplicate']) ?>
        <?php echo $view['form']->end($form) ?>
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
        $(function () {
            $('[data-toggle="popover"]').popover();
        });
        $('#mediaType,#format').change(function () {
            if ($('#mediaType').val() == '' || $('#project').val() == '' || $('#format').val() == '') {
                $('#uniqueId_lbl').hide();
                $('#location_lbl').hide();
                $('#title_lbl').hide();
                $('#printType_lbl').hide();
                $('#mediaDiameter_lbl').hide();
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
                $('#generalNote_lbl').hide();
            } else {
                showUpdateFields();
                $('#uniqueId_lbl').show();
                $('#location_lbl').show();
                $('#printType_lbl').show();
                $('#mediaDiameter_lbl').show();
                $('#title_lbl').show();
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
                $('#generalNote_lbl').show();
            }
        });
    });
</script>
<?php
$view['slots']->stop();
?>
