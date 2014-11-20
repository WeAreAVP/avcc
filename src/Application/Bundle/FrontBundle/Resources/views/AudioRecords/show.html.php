<?php $view->extend('FOSUserBundle::layout.html.php') ?>
<?php $view['slots']->start('body') ?>

<div class="grid fluid">
    <h1>
        <a href="<?php echo $view['router']->generate('record_list') ?>"><i class="icon-arrow-left-3 fg-darker smaller"></i> </a> Record Detail

    </h1>
    <a href="<?php echo $view['router']->generate('record_edit', array('id'=>$entity->getId())) ?>" class="button primary">Edit</a>
    <div class="clearfix"></div>
    <table class="table">
        <tbody>

            <tr>
                <th class="text-right" width="20%">Media Type</th>
                <td width="80%"><?php echo $entity->getRecord()->getMediaType()?></td>
            </tr>
            <tr>
                <th class="text-right">Unique Id</th>
                <td><?php echo $entity->getRecord()->getUniqueId()?></td>
            </tr>
            <tr>
                <th class="text-right">Project Name</th>
                <td><?php echo $entity->getRecord()->getProject()?></td>
            </tr>
            <tr>
                <th class="text-right">Location</th>
                <td><?php echo $entity->getRecord()->getLocation()?></td>
            </tr>
            <tr>
                <th class="text-right">Format</th>
                <td><?php echo $entity->getRecord()->getFormat()?></td>
            </tr>
            <tr>
                <th class="text-right">Title</th>
                <td><?php echo $entity->getRecord()->getTitle()?></td>
            </tr>
            <tr>
                <th class="text-right">Collection Name</th>
                <td><?php echo $entity->getRecord()->getCollectionName()?></td>
            </tr>
            <tr>
                <th class="text-right">Description</th>
                <td><?php echo $entity->getRecord()->getDescription()?></td>
            </tr>
            <?php if($entity->getRecord()->getCommercial()):?>
            <tr>
                <th class="text-right">Commercial</th>
                <td><?php echo $entity->getRecord()->getCommercial()->getName()?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getDiskDiameters()):?>
            <tr>
                <th class="text-right">Disk Diameter</th>
                <td><?php echo $entity->getDiskDiameters()->getName()?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getRecord()->getReelDiameters()):?>
            <tr>
                <th class="text-right">Reel Diameter</th>
                <td><?php echo $entity->getRecord()->getReelDiameters()->getName()?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getMediaDiameters()):?>
            <tr>
                <th class="text-right">Media Diameter</th>
                <td><?php echo $entity->getMediaDiameters()->getName()?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getBases()):?>
            <tr>
                <th class="text-right">Base</th>
                <td><?php echo $entity->getBases()->getName()?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getRecord()->getContentDuration()):?>
            <tr>
                <th class="text-right">Content Duration</th>
                <td><?php echo $entity->getRecord()->getContentDuration()?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getMediaDuration()):?>
            <tr>
                <th class="text-right">Media Duration</th>
                <td><?php echo $entity->getMediaDuration()?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getRecord()->getCreationDate()):?>
            <tr>
                <th class="text-right">Creation Date</th>
                <td><?php echo $entity->getRecord()->getCreationDate()?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getRecord()->getContentDate()):?>
            <tr>
                <th class="text-right">Content Date</th>
                <td><?php echo $entity->getRecord()->getContentDate()?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getRecord()->getIsReview()):?>
            <tr>
                <th class="text-right">Review</th>
                <td><?php echo ($entity->getRecord()->getIsReview()) ? 'Yes' : 'No'?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getRecordingSpeed()):?>
            <tr>
                <th class="text-right">Recording Speed</th>
                <td><?php echo $entity->getRecordingSpeed() ?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getTapeThickness()):?>
            <tr>
                <th class="text-right">Tape Thickness</th>
                <td><?php echo $entity->getTapeThickness() ?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getSlides()):?>
            <tr>
                <th class="text-right">Slide</th>
                <td><?php echo $entity->getSlides() ?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getTrackTypes()):?>
            <tr>
                <th class="text-right">Track Type</th>
                <td><?php echo $entity->getTrackTypes() ?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getMonoStereo()):?>
            <tr>
                <th class="text-right">Mono or Stereo</th>
                <td><?php echo $entity->getMonoStereo() ?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getNoiceReduction()):?>
            <tr>
                <th class="text-right">Noise Reduction </th>
                <td><?php echo $entity->getNoiceReduction() ?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getRecord()->getGenreTerms()):?>
            <tr>
                <th class="text-right">Genre Terms </th>
                <td><?php echo $entity->getRecord()->getGenreTerms() ?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getRecord()->getContributor()):?>
            <tr>
                <th class="text-right">Contributor </th>
                <td><?php echo $entity->getRecord()->getContributor() ?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getRecord()->getGeneration()):?>
            <tr>
                <th class="text-right">Generation </th>
                <td><?php echo $entity->getRecord()->getGeneration() ?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getRecord()->getPart()):?>
            <tr>
                <th class="text-right">Part </th>
                <td><?php echo $entity->getRecord()->getPart() ?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getRecord()->getCopyrightRestrictions()):?>
            <tr>
                <th class="text-right">Copyright Restrictions </th>
                <td><?php echo $entity->getRecord()->getCopyrightRestrictions() ?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getRecord()->getDuplicatesDerivatives()):?>
            <tr>
                <th class="text-right">Duplicates Derivatives </th>
                <td><?php echo $entity->getRecord()->getDuplicatesDerivatives() ?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getRecord()->getRelatedMaterial()):?>
            <tr>
                <th class="text-right">Related Material </th>
                <td><?php echo $entity->getRecord()->getRelatedMaterial() ?></td>
            </tr>
            <?php endif;?>
            <?php if($entity->getRecord()->getConditionNote()):?>
            <tr>
                <th class="text-right">Condition Notes </th>
                <td><?php echo $entity->getRecord()->getConditionNote() ?></td>
            </tr>
            <?php endif;?>
            </tbody>
    </table>
</div>

<?php
$view['slots']->stop();
?>
