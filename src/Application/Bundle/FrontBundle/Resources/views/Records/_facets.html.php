<nav id="facet_sidebar" class="sidebar light">
    <?php // print_r($facets);exit;?>
    <ul>
        <form id='formSearch' name='formSearch' method='post'>
            <?php $facetData = $app->getSession()->get('facetData');?>
            <li class="title">Filters</li>
            <li><a href="#">Keyword</a></li>
                <?php if (count($facets['mediaType']) > 0): ?>
                <li>
                    <a class="dropdown-toggle" href="#">Media Type</a>                                
                    <ul data-role="dropdown" <?php if (isset($facetData['mediaType'])): ?> style="display:block" <?php endif;?>>
                        <?php foreach ($facets['mediaType'] as $mediaType): ?>
                            <li><a href="javascript://"><label for="<?php echo $mediaType['media_type'] ?>"><input id='<?php echo $mediaType['media_type']?>' type="checkbox" class="facet_checkbox" name="mediaType[]" value="<?php echo $mediaType['media_type']?>" /><?php echo $mediaType['media_type']?> (<?php echo $mediaType['total']?>)</label></a></li>
                                    <?php endforeach; ?>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if (count($facets['formats']) > 0): ?>   
                <li>
                    <a class="dropdown-toggle" href="#">Format</a>                                
                    <ul data-role="dropdown" <?php if (isset($facetData['format'])): ?> style="display:block" <?php endif;?>>
                        <?php foreach ($facets['formats'] as $format): ?>
                            <li><a href="javascript://"><label for="<?php echo $format['format']?>"><input id='<?php echo $format['format']?>' type="checkbox" class="facet_checkbox" name="format[]" value="<?php echo $format['format']?>" /><?php echo $format['format']?> (<?php echo $format['total']?>)</label></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endif; ?>
            <li>
                <a class="dropdown-toggle" href="#">Commercial/Unique</a>                                
                <ul  data-role="dropdown" <?php if (isset($facetData['commercial'])): ?> style="display:block" <?php endif;?>>
                    <?php foreach ($facets['commercialUnique'] as $cOrU): ?>
                        <?php if ($cOrU['commercial'] != ''): ?>  
                            <li><a href="javascript://"><label for="<?php echo $cOrU['commercial']?>"><input id='<?php echo $cOrU['commercial']?>' type="checkbox" class="facet_checkbox" name="commercial[]" value="<?php echo $cOrU['commercial']?>" /><?php echo $cOrU['commercial']?> (<?php echo $cOrU['total']?>)</label></a></li>
                                    <?php endif;?>
                                <?php endforeach; ?>
                </ul>
            </li>
            <li>
                <a class="dropdown-toggle" href="#">Base</a>                                
                <ul  data-role="dropdown" <?php if (isset($facetData['base'])): ?> style="display:block" <?php endif;?>>
                    <?php foreach ($facets['bases'] as $base): ?>
                        <?php if ($base['base'] != ''): ?>  
                            <li><a href="javascript://"><label for="<?php echo $base['base']?>"><input id='<?php echo $base['base']?>' type="checkbox" class="facet_checkbox" name="base[]" value="<?php echo $base['base']?>" /><?php echo $base['base']?> (<?php echo $base['total']?>)</label></a></li>
                                    <?php endif?>
                                <?php endforeach ?>
                </ul>
            </li>
            <li>
                <a class="dropdown-toggle" href="#">Collection Name</a>                                
                <ul  data-role="dropdown" <?php if (isset($facetData['collectionName'])): ?> style="display:block" <?php endif?>>
                    <?php foreach ($facets['collectionNames'] as $collectionName): ?>
                        <?php if ($collectionName['collection_name'] != ''): ?>  
                            <li><a href="javascript://"><label for="<?php echo $collectionName['collection_name']?>"><input id='<?php echo $collectionName['collection_name']?>' type="checkbox" class="facet_checkbox" name="collectionName[]" value="<?php echo $collectionName['collection_name']?>" /><?php echo $collectionName['collection_name']?> (<?php echo $collectionName['total']?>)</label></a></li>
                                    <?php endif?>
                                <?php endforeach ?>
                </ul>
            </li>
            <li>
                <a class="dropdown-toggle" href="#">Recording Standard</a>                                
                <ul  data-role="dropdown" <?php if (isset($facetData['recordingStandard'])): ?> style="display:block" <?php endif?>>
                    <?php foreach ($facets['recordingStandards'] as $recordingStandard): ?>
                        <?php if ($recordingStandard['recording_standard']): ?>  
                            <li><a href="javascript://"><label for="<?php echo $recordingStandard['recording_standard']?>"><input id='<?php echo $recordingStandard['recording_standard']?>' type="checkbox" class="facet_checkbox" name="recordingStandard[]" value="<?php echo $recordingStandard['recording_standard']?>" /><?php echo $recordingStandard['recording_standard']?> (<?php echo $recordingStandard['total']?>)</label></a></li>
                                    <?php endif?>
                                <?php endforeach; ?>
                </ul>
            </li>
            <li>
                <a class="dropdown-toggle" href="#">Print Type</a>                                
                <ul  data-role="dropdown" <?php if (isset($facetData['printType'])): ?> style="display:block" <?php endif?>>
                    <?php foreach ($facets['printTypes'] as $printType): ?>
                        <?php if ($printType['print_type']): ?>  
                            <li><a href="javascript://"><label for="<?php echo $printType['print_type']?>"><input id='<?php echo $printType['print_type']?>' type="checkbox" class="facet_checkbox" name="printType[]" value="<?php echo $printType['print_type']?>" /><?php echo $printType['print_type']?> (<?php echo $printType['total']?>)</label></a></li>
                                    <?php endif?>
                                <?php endforeach ?>
                </ul>
            </li>
            <li>
                <a class="dropdown-toggle" href="#">Project Name</a>                                
                <ul  data-role="dropdown" <?php if (isset($facetData['project'])): ?> style="display:block" <?php endif?>>
                    <?php foreach ($facets['projectNames'] as $projectName): ?>
                        <?php if ($projectName['project']): ?>  
                            <li><a href="javascript://"><label for="<?php echo $projectName['project']?>"><input id='<?php echo $projectName['project']?>' type="checkbox" class="facet_checkbox" name="project[]" value="<?php echo $projectName['project']?>" /><?php echo $projectName['project']?> (<?php echo $projectName['total']?>)</label></a></li>
                                    <?php endif?>
                                <?php endforeach; ?>
                </ul>
            </li>
            <li>
                <a class="dropdown-toggle" href="#">Reel Diameter</a>                                
                <ul  data-role="dropdown" <?php if (isset($facetData['reelDiameter'])): ?> style="display:block" <?php endif?>>
                    <?php foreach ($facets['reelDiameters'] as $reelDiameter): ?>
                        <?php if ($reelDiameter['reel_diameter']): ?>  
                            <li><a href="javascript://"><label for="<?php echo $reelDiameter['reel_diameter']?>"><input id='<?php echo $reelDiameter['reel_diameter']?>' type="checkbox" class="facet_checkbox" name="reelDiameter[]" value="<?php echo $reelDiameter['reel_diameter']?>" /><?php echo $reelDiameter['reel_diameter']?> (<?php echo $reelDiameter['total']?>)</label></a></li>
                                    <?php endif?>
                                <?php endforeach ?>
                </ul>
            </li>
            <li>
                <a class="dropdown-toggle" href="#">Disc Diameter</a>                                
                <ul  data-role="dropdown" <?php if (isset($facetData['discDiameter'])): ?> style="display:block" <?php endif?>>
                    <?php foreach ($facets['discDiameters'] as $discDiameter): ?>
                        <?php if ($discDiameter['disk_diameter'] != ''): ?>  
                            <li><a href="javascript://"><label for="<?php echo $discDiameter['disk_diameter']?>"><input id='<?php echo $discDiameter['disk_diameter']?>' type="checkbox" class="facet_checkbox" name="discDiameter[]" value="<?php echo $discDiameter['disk_diameter']?>" /><?php echo $discDiameter['disk_diameter']?> (<?php echo $discDiameter['disk_diameter']?>)</label></a></li>
                                    <?php endif?>
                                <?php endforeach; ?>
                </ul>
            </li>
            <li>
                <a class="dropdown-toggle" href="#">Acid Detection Strip</a>                                
                <ul  data-role="dropdown" <?php if (isset($facetData['acidDetection'])): ?> style="display:block" <?php endif?>>
                    <?php foreach ($facets['acidDetection'] as $strip): ?>
                        <?php if($strip['acid_detection']): ?>  
                            <li><a href="javascript://"><label for="<?php echo $strip['acid_detection']?>"><input id='<?php echo $strip['acid_detection']?>' type="checkbox" class="facet_checkbox" name="acidDetection[]" value="<?php echo $strip['acid_detection']?>" /><?php echo $strip['acid_detection']?> (<?php echo $strip['total']?>)</label></a></li>
                                    <?php endif?>
                                <?php endforeach; ?>
                </ul>
            </li>
            <li class="chekBoxFacet">
                <span id="is_review_check" style="cursor: default;">
                    <?php $review_check = 0 ?>
                    <?php if (isset($facetData['is_review'])): ?>
                        <?php $review_check = $facetData['is_review'] ?>
                    <?php endif; ?>    
                    <input type="hidden" id="is_review_check_state" name="is_review_check" value="<?php echo  $review_check ?>"/> 
                </span>
                Review
            </li>            
        </form>

    </ul>
</nav>
<input type="hidden" value="" name="parent_facet" id="parent_facet" />
<input type="hidden" value="" name="total_checked" id="total_checked"/>
<script type="text/javascript" src="<?php echo  asset('js/tristate-0.9.2.js') ?>"></script> 
<script type="text/javascript">
    initTriStateCheckBox('is_review_check', 'is_review_check_state', true);
    $(window).load(function () {
        var facets = new Records();
        facets.setAjaxSource('<?php echo  $view['router']->generate('record_dataTable') ?>');
        facets.setPageUrl('<?php echo  $view['router']->generate('record_list') ?>');
        facets.bindEvents();
    });
</script>