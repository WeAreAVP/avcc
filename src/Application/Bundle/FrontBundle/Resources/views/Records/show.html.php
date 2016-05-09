<?php $view->extend('FOSUserBundle::layout.html.php') ?>
<?php $view['slots']->start('body') ?>

<div class="grid fluid">
    <h1>
        <a href="<?php echo $view['router']->generate('record_list') ?>"><i class="icon-arrow-left-3 smaller"></i> </a> Record Detail

    </h1>
    <?php
    if ($entity->getMediaType()->getId() == 1):
        $id = $entity->getAudioRecord()->getId();
        $rout = 'record_edit';
    elseif ($entity->getMediaType()->getId() == 3):
        $id = $entity->getVideoRecord()->getId();
        $rout = 'record_video_edit';
    else:
        $id = $entity->getFilmRecord()->getId();
        $rout = 'record_film_edit';
    endif;
    ?>
    <?php if ($view['security']->isGranted('ROLE_CATALOGER')): ?>
        <a href="<?php echo $view['router']->generate($rout, array('id' => $id)) ?>" class="button primary">Edit</a>
    <?php endif; ?>
    <div class="clearfix"></div>
    <div class="grid">
        <div class="row">
            <div class="span4">Created by: <?php echo ($entity->getUser()) ? $entity->getUser()->getName(): ""; ?></div>
            <?php if ($entity->getEditor()) : ?>
                <div class="span4">Last Modified by: <?php echo $entity->getEditor()->getName(); ?></div>
            <?php endif;
            ?>
        </div>
        <div class="row">
            <div class="span4">Created at: <?php echo $entity->getCreatedOn()->format('Y-m-d H:i:s'); ?></div>
            <?php if ($entity->getUpdatedOn()): ?>
                <div class="span4">Last Modified at:  <?php echo $entity->getUpdatedOn()->format('Y-m-d H:i:s'); ?>
                </div>
                <?php
            endif;
            ?>
        </div>

    </div>
    <table class="table">
        <tbody>            
            <?php $type = strtolower($entity->getMediaType()); ?>
            <?php foreach ($fieldSettings[$type] as $typeField): ?>
                <?php
                $field = explode('.', $typeField['field']);
                $arrayIndex = (count($field) == 2) ? $field[1] : $field[0];
                if ($entityArray[$arrayIndex]) {
                    ?>
                    <tr style="<?php echo ($typeField['hidden']) ? 'display:none;' : ''; ?>">
                        <th class="text-right" width="20%">
                            <p class="label_class" data-toggle="popover" data-placement="bottom" data-content="<?php echo isset($tooltip[$arrayIndex]) ? $tooltip[$arrayIndex] : ''; ?>">
                            <?php echo $typeField['title']; ?>
                            </p>
                        </th>
                        <td width="80%"><?php echo $entityArray[$arrayIndex] ?></td>
                    </tr> 
                <?php } ?>  
            <?php endforeach; ?>                          
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(function () {
            $('[data-toggle="popover"]').popover();
        });
    });

</script>
<?php
$view['slots']->stop();
