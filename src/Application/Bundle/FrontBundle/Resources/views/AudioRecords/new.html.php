<?php $view->extend('FOSUserBundle::layout.html.php') ?>
<?php $view['slots']->start('body') ?>

<div class="grid fluid">
    <h1>
        <a href="<?php echo $view['router']->generate('record_add') ?>"><i class="icon-arrow-left-3 fg-darker smaller"></i> </a>
        New Record - <?php echo ucwords($type)?>
    </h1>   
    <?php echo $view['form']->start($form) ?>
    <?php echo $view['form']->errors($form) ?>
    <fieldset>
        <?php echo $view['form']->errors($form) ?>
        <?php foreach ($fieldSettings['audio'] as $audioField): ?>
            <?php
            $field = explode('.', $audioField['field']);
            ?>
        <div style="<?php echo ($audioField['hidden']) ? 'display:none;' : ''; ?>" class="col-lg-6">
                <?php echo $view['form']->label((count($field) == 2) ? $form[$field[0]][$field[1]] : $form[$field[0]]) ?>
            <div class="input-control text" data-role="input-control">
    <?php echo $view['form']->widget((count($field) == 2) ? $form[$field[0]][$field[1]] : $form[$field[0]], array('attr' => array('class' => 'size4'))) ?>            
                <span class="has-error text-danger"><?php echo $view['form']->errors((count($field) == 2) ? $form[$field[0]][$field[1]] : $form[$field[0]]) ?></span>
            </div>
        </div>
    <?php endforeach; ?>
    </fieldset>
    <?php echo $view['form']->end($form) ?>
</div>

<?php $view['slots']->stop();

