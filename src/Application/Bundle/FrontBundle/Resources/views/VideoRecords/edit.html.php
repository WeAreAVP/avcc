<?php $view->extend('FOSUserBundle::layout.html.php') ?>
<?php $view['slots']->start('body') ?>

<div class="grid fluid">
    <h1>
        <a href="<?php echo $view['router']->generate('record_list') ?>"><i class="icon-arrow-left-3 smaller"></i> </a>
        Edit Record<?php // echo ucwords($type)     ?>
    </h1>
    <?php // echo $view['form']->widget($edit_form); exit;?>
    <?php echo $view['form']->start($edit_form) ?>
    <?php echo $view['form']->errors($edit_form) ?>
    <fieldset>

        <?php // echo $view['form']->widget($edit_form)
        ?>
        <?php foreach ($fieldSettings['video'] as $viedoField): ?>
            <?php
            $field = explode('.', $viedoField['field']);
            if (count($field) == 2) {
                $index = $field[1];
            } else {
                $index = $field[0];
            }
            $_style = (count($field) == 2 && $field[1] == 'isReview' || count($field) == 2 && $field[1] == 'reformattingPriority') ? 'width: 180px;float: left;margin-bottom: 15px;' : 'width: 200px';
            ?>
            <div style="<?php echo ($viedoField['hidden']) ? 'display:none;' : ''; ?>" class="col-lg-6" id="<?php echo (count($field) == 2) ? $field[1] . '_lbl' : $field[0] . '_lbl' ?>" data-view="<?php echo ($viedoField['hidden']) ? 'hide' : 'show'; ?>">
                <div class="label_class" data-toggle="popover" data-placement="bottom" data-content="<?php echo isset($tooltip[$index]) ? $tooltip[$index] : ''; ?>" style="<?php echo  $_style ?>">
                    <?php
                    $attr = ($viedoField['is_required']) ? array('class' => 'size4') : array('class' => 'size4');
                    echo $view['form']->label((count($field) == 2) ? $edit_form[$field[0]][$field[1]] : $edit_form[$field[0]], ' ');
                    echo $viedoField['title'];
                    echo ($viedoField['is_required']) ? "&nbsp;<span>*</span>" : "";
                    ?>
                </div>
                <?php
                    if (count($field) == 2 && $field[1] == 'isReview' || count($field) == 2 && $field[1] == 'reformattingPriority')
                        $style = '';
                    else if (count($field) == 2 && $field[1] == 'conditionNote' || count($field) == 2 && $field[1] == 'generalNote' || count($field) == 2 && $field[1] == 'copyrightRestrictions' || count($field) == 2 && $field[1] == 'description')
                         $style = 'textarea';
                    else
                        $style = 'text';
                    ?>
                <div class="input-control <?php echo $style; ?> edit" data-role="input-control">
                    <?php
                    $_attr = (count($field) == 2 && $field[1] == 'isReview' || count($field) == 2 && $field[1] == 'reformattingPriority') ? array() : $attr;
                    ?>                    
                    <?php echo $view['form']->widget((count($field) == 2) ? $edit_form[$field[0]][$field[1]] : $edit_form[$field[0]], array('id' => (count($field) == 2) ? $field[1] : $field[0], 'attr' => $_attr)) ?>
                    <span class="has-error text-danger"><?php echo $view['form']->errors((count($field) == 2) ? $edit_form[$field[0]][$field[1]] : $edit_form[$field[0]]) ?></span>
                </div>
            </div>

        <?php endforeach; ?>
    </fieldset>
    <?php echo $view['form']->widget($edit_form['record']['userId']) ?>
    <?php echo $view['form']->widget($edit_form['record']['mediaTypeHidden']) ?>
    <a href="<?php echo $view['router']->generate('record_list') ?>" name="cancle" class="button">Cancel</a>
    <?php echo $view['form']->widget($edit_form['submit'], array('attr' => array('class' => 'button primary'))) ?>
    <?php echo $view['form']->widget($edit_form['save_and_new']) ?>
    <?php echo $view['form']->widget($edit_form['save_and_duplicate']) ?>
    <?php echo $view['form']->widget($edit_form['delete']) ?>
    <?php echo $view['form']->end($edit_form) ?>
</div>
<script src="<?php echo $view['assets']->getUrl('js/manage.records.js') ?>"></script>
<script type="text/javascript">
    var changes = false;
    var baseUrl = '<?php echo $view['router']->generate('record') ?>';
    var selectedFormat = '<?php echo ($entity->getRecord() && $entity->getRecord()->getFormat()) ? $entity->getRecord()->getFormat()->getId() : ''; ?>';
    var selectedMediaType = '<?php echo $entity->getRecord()->getMediaType()->getId(); ?>';
    var selectedFormatVersion = '<?php echo ($entity->getFormatVersion()) ? $entity->getFormatVersion()->getId() : ''; ?>';
    var selectedRS = '<?php echo ($entity->getRecordingSpeed()) ? $entity->getRecordingSpeed()->getId() : ''; ?>';
    var selectedRD = '<?php echo ($entity->getRecord() && $entity->getRecord()->getReelDiameters()) ? $entity->getRecord()->getReelDiameters()->getId() : ''; ?>';
    var selectedProject = '<?php echo ($entity->getRecord() && $entity->getRecord()->getProject()) ? $entity->getRecord()->getProject()->getId() : ''; ?>';
    var viewUrl = baseUrl + 'video/<?php echo $entity->getId(); ?>/edit/';
    var projectId = 0;
    var selectedbase = '';
    $(document).ready(function () {
        initialize_records_form();
        $(function () {
            $('[data-toggle="popover"]').popover();
        });
    });
</script>
<?php
$view['slots']->stop();
?>
