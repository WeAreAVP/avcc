<?php $view->extend('FOSUserBundle::layout.html.php') ?>
<?php $view['slots']->start('body') ?>

<div class="grid fluid">
    <h1>
        <a href="<?php echo $view['router']->generate('record') ?>"><i class="icon-arrow-left-3 fg-darker smaller"></i> </a>
        <?php // echo ucwords($type) ?>
    </h1> 
    <table class="record_properties">
        <tbody>
    <?php // echo $entity->getMediaType();exit;
    foreach ($fieldSettings[strtolower($entity->getMediaType())] as $recordField): ?>
       <?php
            $field = explode('.', $recordField['field']);
//            print_r($field);
//            $function = count($field==2)?'get'.ucwords($field[1]).'()':'get'.$entity->getMediaType().'()->get'.ucwords($field[0]).'()';            
//            $fun =  '$entity->'.$function;
//            echo '<br />';
            
       ?>
            <tr>
                <th><?php // echo $recordField['title']?></th>
                <td><?php // echo ($function) ? exec($fun) :$fun?></td>
            </tr>
        
    <?php endforeach;?>
            </tbody>
    </table>
</div>

<?php
$view['slots']->stop();
?>
