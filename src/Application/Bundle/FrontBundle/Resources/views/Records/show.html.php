<?php $view->extend('FOSUserBundle::layout.html.php') ?>
<?php $view['slots']->start('body') ?>

<div class="grid fluid">
    <h1>
        <a href="<?php echo $view['router']->generate('record_list') ?>"><i class="icon-arrow-left-3 fg-darker smaller"></i> </a> Record Detail

    </h1>
    <?php
    if ($entity->getAudioRecord()):
        $id = $entity->getAudioRecord()->getId();
        $rout = 'record_edit';
    elseif ($entity->getVideoRecord()):
        $id = $entity->getVideoRecord()->getId();
        $rout = 'record_video_edit';
    else:
        $id = $entity->getFilmRecord()->getId();
        $rout = 'record_film_edit';
    endif;
    ?>
    <a href="<?php echo $view['router']->generate($rout, array('id' => $id)) ?>" class="button primary">Edit</a>
    <div class="clearfix"></div>
    <div class="grid">
        <div class="row">
            <div class="span4">Created by: <?php echo $entity->getUser()->getName(); ?></div>
            <?php if ($entity->getEditor()) : ?>
                <div class="span4">Modified By: <?php echo $entity->getEditor()->getName(); ?></div>
            <?php endif;
            ?>
        </div>
        <div class="row">
            <div class="span4">Created at: <?php echo $entity->getCreatedOn()->format('Y-m-d H:i:s'); ?></div>
            <?php if ($entity->getUpdatedOn()): ?>
                <div class="span4">Modified at:  <?php echo $entity->getUpdatedOn()->format('Y-m-d H:i:s'); ?>
                </div>
                <?php
            endif;
            ?>
        </div>

    </div>
    <table class="table">
        <tbody>

            <tr>
                <th class="text-right" width="20%">Media Type</th>
                <td width="80%"><?php echo $entity->getMediaType() ?></td>
            </tr>
            <tr>
                <th class="text-right">Unique Id</th>
                <td><?php echo $entity->getUniqueId() ?></td>
            </tr>
            <tr>
                <th class="text-right">Project Name</th>
                <td><?php echo $entity->getProject() ?></td>
            </tr>
            <tr>
                <th class="text-right">Location</th>
                <td><?php echo $entity->getLocation() ?></td>
            </tr>
            <tr>
                <th class="text-right">Format</th>
                <td><?php echo $entity->getFormat() ?></td>
            </tr>
            <?php if ($entity->getFilmRecord()): ?>
                <?php if ($entity->getFilmRecord()->getPrintType()): ?>
                    <tr>
                        <th class="text-right">Print Type</th>
                        <td><?php echo $entity->getFilmRecord()->getPrintType() ?></td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>
            <tr>
                <th class="text-right">Title</th>
                <td><?php echo $entity->getTitle() ?></td>
            </tr>
            <tr>
                <th class="text-right">Collection Name</th>
                <td><?php echo $entity->getCollectionName() ?></td>
            </tr>
            <tr>
                <th class="text-right">Description</th>
                <td><?php echo $entity->getDescription() ?></td>
            </tr>
            <?php if ($entity->getCommercial()): ?>
                <tr>
                    <th class="text-right">Commercial</th>
                    <td><?php echo $entity->getCommercial()->getName() ?></td>
                </tr>
            <?php endif; ?>
            <?php if ($entity->getAudioRecord()): ?>  
                <?php if ($entity->getAudioRecord()->getDiskDiameters()): ?>
                    <tr>
                        <th class="text-right">Disk Diameter</th>
                        <td><?php echo $entity->getAudioRecord()->getDiskDiameters()->getName() ?></td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>  
            <?php if ($entity->getVideoRecord()): ?>          
                <?php if ($entity->getVideoRecord()->getCassetteSize()): ?>
                    <tr>
                        <th class="text-right">Cassette Size</th>
                        <td><?php echo $entity->getVideoRecord()->getCassetteSize()->getName() ?></td>
                    </tr>
                <?php endif; ?>    
            <?php endif; ?>  
            <?php if ($entity->getFilmRecord()): ?> 
                <?php if ($entity->getFilmRecord()->getReelCore()): ?>
                    <tr>
                        <th class="text-right">Reel or Core</th>
                        <td><?php echo $entity->getFilmRecord()->getReelCore()->getName() ?></td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>         
            <?php if ($entity->getReelDiameters()): ?>
                <tr>
                    <th class="text-right">Reel Diameter</th>
                    <td><?php echo $entity->getReelDiameters()->getName() ?></td>
                </tr>
            <?php endif; ?>
            <?php if ($entity->getAudioRecord()): ?>  
                <?php if ($entity->getAudioRecord()->getMediaDiameters()): ?>
                    <tr>
                        <th class="text-right">Media Diameter</th>
                        <td><?php echo $entity->getAudioRecord()->getMediaDiameters()->getName() ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($entity->getAudioRecord()->getBases()): ?>
                    <tr>
                        <th class="text-right">Base</th>
                        <td><?php echo $entity->getAudioRecord()->getBases()->getName() ?></td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($entity->getFilmRecord()): ?> 
                <?php if ($entity->getFilmRecord()->getFootage()): ?>
                    <tr>
                        <th class="text-right">Footage</th>
                        <td><?php echo $entity->getFilmRecord()->getFootage() ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($entity->getFilmRecord()->getMediaDiameter()): ?>
                    <tr>
                        <th class="text-right">Media Diameter</th>
                        <td><?php echo $entity->getFilmRecord()->getMediaDiameter() ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($entity->getFilmRecord()->getBases()): ?>
                    <tr>
                        <th class="text-right">Base</th>
                        <td><?php echo $entity->getFilmRecord()->getBases()->getName() ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($entity->getFilmRecord()->getColors()): ?>
                    <tr>
                        <th class="text-right">Color</th>
                        <td><?php echo $entity->getFilmRecord()->getColors()->getName() ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($entity->getFilmRecord()->getSound()): ?>
                    <tr>
                        <th class="text-right">Sound</th>
                        <td><?php echo $entity->getFilmRecord()->getSound()->getName() ?></td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>        
            <?php if ($entity->getContentDuration()): ?>
                <tr>
                    <th class="text-right">Content Duration</th>
                    <td><?php echo $entity->getContentDuration() ?></td>
                </tr>
            <?php endif; ?>
            <?php if ($entity->getAudioRecord()): ?>
                <?php if ($entity->getAudioRecord()->getMediaDuration()): ?>
                    <tr>
                        <th class="text-right">Media Duration</th>
                        <td><?php echo $entity->getAudioRecord()->getMediaDuration() ?></td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($entity->getVideoRecord()): ?>
                <?php if ($entity->getVideoRecord()->getMediaDuration()): ?>
                    <tr>
                        <th class="text-right">Media Duration</th>
                        <td><?php echo $entity->getVideoRecord()->getMediaDuration() ?></td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>        
            <?php if ($entity->getCreationDate()): ?>
                <tr>
                    <th class="text-right">Creation Date</th>
                    <td><?php echo $entity->getCreationDate() ?></td>
                </tr>
            <?php endif; ?>
            <?php if ($entity->getContentDate()): ?>
                <tr>
                    <th class="text-right">Content Date</th>
                    <td><?php echo $entity->getContentDate() ?></td>
                </tr>
            <?php endif; ?>
            <?php if ($entity->getIsReview()): ?>
                <tr>
                    <th class="text-right">Review</th>
                    <td><?php echo ($entity->getIsReview()) ? 'Yes' : 'No' ?></td>
                </tr>
            <?php endif; ?>
            <?php if ($entity->getAudioRecord()): ?>     
                <?php if ($entity->getAudioRecord()->getRecordingSpeed()): ?>
                    <tr>
                        <th class="text-right">Recording Speed</th>
                        <td><?php echo $entity->getAudioRecord()->getRecordingSpeed() ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($entity->getAudioRecord()->getTapeThickness()): ?>
                    <tr>
                        <th class="text-right">Tape Thickness</th>
                        <td><?php echo $entity->getAudioRecord()->getTapeThickness() ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($entity->getAudioRecord()->getSlides()): ?>
                    <tr>
                        <th class="text-right">Sides</th>
                        <td><?php echo $entity->getAudioRecord()->getSlides() ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($entity->getAudioRecord()->getTrackTypes()): ?>
                    <tr>
                        <th class="text-right">Track Type</th>
                        <td><?php echo $entity->getAudioRecord()->getTrackTypes() ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($entity->getAudioRecord()->getMonoStereo()): ?>
                    <tr>
                        <th class="text-right">Mono or Stereo</th>
                        <td><?php echo $entity->getAudioRecord()->getMonoStereo() ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($entity->getAudioRecord()->getNoiceReduction()): ?>
                    <tr>
                        <th class="text-right">Noise Reduction </th>
                        <td><?php echo $entity->getAudioRecord()->getNoiceReduction() ?></td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?> 
            <?php if ($entity->getVideoRecord()): ?>        
                <?php if ($entity->getVideoRecord()->getFormatVersion()): ?>
                    <tr>
                        <th class="text-right">Format Version</th>
                        <td><?php echo $entity->getVideoRecord()->getFormatVersion() ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($entity->getVideoRecord()->getRecordingSpeed()): ?>
                    <tr>
                        <th class="text-right">Recording Speed</th>
                        <td><?php echo $entity->getVideoRecord()->getRecordingSpeed() ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($entity->getVideoRecord()->getRecordingStandard()): ?>
                    <tr>
                        <th class="text-right">Recording Standard</th>
                        <td><?php echo $entity->getVideoRecord()->getRecordingStandard() ?></td>
                    </tr>
                <?php endif; ?>  
            <?php endif; ?>
            <?php if ($entity->getFilmRecord()): ?>
                <?php if ($entity->getFilmRecord()->getFrameRate()): ?>
                    <tr>
                        <th class="text-right">Frame Rate</th>
                        <td><?php echo $entity->getFilmRecord()->getFrameRate() ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($entity->getFilmRecord()->getAcidDetectionStrip()): ?>
                    <tr>
                        <th class="text-right">Acid Detection Strip</th>
                        <td><?php echo $entity->getFilmRecord()->getAcidDetectionStrip() ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($entity->getFilmRecord()->getShrinkage()): ?>
                    <tr>
                        <th class="text-right">Shrinkage</th>
                        <td><?php echo $entity->getFilmRecord()->getShrinkage() ?></td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>        
            <?php if ($entity->getGenreTerms()): ?>
                <tr>
                    <th class="text-right">Genre Terms </th>
                    <td><?php echo $entity->getGenreTerms() ?></td>
                </tr>
            <?php endif; ?>
            <?php if ($entity->getContributor()): ?>
                <tr>
                    <th class="text-right">Contributor </th>
                    <td><?php echo $entity->getContributor() ?></td>
                </tr>
            <?php endif; ?>
            <?php if ($entity->getGeneration()): ?>
                <tr>
                    <th class="text-right">Generation </th>
                    <td><?php echo $entity->getGeneration() ?></td>
                </tr>
            <?php endif; ?>
            <?php if ($entity->getPart()): ?>
                <tr>
                    <th class="text-right">Part </th>
                    <td><?php echo $entity->getPart() ?></td>
                </tr>
            <?php endif; ?>
            <?php if ($entity->getCopyrightRestrictions()): ?>
                <tr>
                    <th class="text-right">Copyright / Restrictions</th>
                    <td><?php echo $entity->getCopyrightRestrictions() ?></td>
                </tr>
            <?php endif; ?>
            <?php if ($entity->getDuplicatesDerivatives()): ?>
                <tr>
                    <th class="text-right">Duplicates Derivatives </th>
                    <td><?php echo $entity->getDuplicatesDerivatives() ?></td>
                </tr>
            <?php endif; ?>
            <?php if ($entity->getRelatedMaterial()): ?>
                <tr>
                    <th class="text-right">Related Material </th>
                    <td><?php echo $entity->getRelatedMaterial() ?></td>
                </tr>
            <?php endif; ?>
            <?php if ($entity->getConditionNote()): ?>
                <tr>
                    <th class="text-right">Condition Notes </th>
                    <td><?php echo $entity->getConditionNote() ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$view['slots']->stop();
?>
