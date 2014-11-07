<?php $view->extend('FOSUserBundle::layout.html.php') ?>
<?php $view['slots']->start('body') ?>

<div class="grid fluid">
    <h1>
        <a href="<?php echo $view['router']->generate('record') ?>"><i class="icon-arrow-left-3 fg-darker smaller"></i> </a>
        New Record - <?php echo ucwords($type) ?>
    </h1>   
    <?php echo $view['form']->start($form) ?>
    <?php echo $view['form']->errors($form) ?>
    <fieldset>
        <?php echo $view['form']->errors($form) ?>
        <?php // echo $view['form']->widget($form) ?>  
        <?php foreach ($fieldSettings[strtolower($type)] as $filmField): ?>
            <?php
            $field = explode('.', $filmField['field']);
            ?>
            <div style="<?php echo ($filmField['hidden']) ? 'display:none;' : ''; ?>" class="col-lg-6" id="<?php echo (count($field) == 2) ? $field[1].'_lbl' : $field[0].'_lbl' ?>">
                <?php echo $view['form']->label((count($field) == 2) ? $form[$field[0]][$field[1]] : $form[$field[0]]) ?>
                <div class="input-control text" data-role="input-control">
                    <?php echo $view['form']->widget((count($field) == 2) ? $form[$field[0]][$field[1]] : $form[$field[0]], array('id' => (count($field) == 2) ? $field[1] : $field[0], 'attr' => array('class' => 'size4'))) ?>            
                    <span class="has-error text-danger"><?php echo $view['form']->errors((count($field) == 2) ? $form[$field[0]][$field[1]] : $form[$field[0]]) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </fieldset>
    <?php echo $view['form']->widget($form['record']['userId']) ?>   
    <?php echo $view['form']->widget($form['record']['mediaTypeHidden']) ?>   
    <?php // echo $view['form']->widget($form['record']['projectHidden']) ?>   
    <?php echo $view['form']->end($form) ?>
</div>
<script src="<?php echo $view['assets']->getUrl('js/manage.records.js') ?>"></script>
<script type="text/javascript">  
    var baseUrl = '<?php echo $view['router']->generate('record')?>';
    $(document).ready(function () {
        initialize_records_form();    
    });
</script>
<?php
$view['slots']->stop();
?>

