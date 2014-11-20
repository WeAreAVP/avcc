<nav id="facet_sidebar" class="sidebar light">
    <?php // print_r($facets);exit;?>
    <form id='formSearch' name='formSearch' method='post'>
        <ul>
            <?php
            $facetData = $app->getSession()->get('facetData');
//            print_r($facetData);exit;
            ?>
            <li class="title">Filters</li>
            <li><a href="#">Keyword</a></li>
<?php if (count($facets['mediaType']) > 0): ?>
                <li>
                    <a class="dropdown-toggle" href="#">Media Type</a>                                
                    <ul data-role="dropdown" <?php if (isset($facetData['mediaType'])): ?> style="display:block" <?php endif; ?>>
                        <?php foreach ($facets['mediaType'] as $mediaType): ?>
                            <li><a href="javascript://"><label for="<?php echo $mediaType['media_type'] ?>"><input id='<?php echo $mediaType['media_type'] ?>' <?php echo (isset($facetData['mediaType']) && in_array($mediaType['media_type'], $facetData['mediaType'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="mediaType[]" value="<?php echo $mediaType['media_type'] ?>" /><?php echo $mediaType['media_type'] ?> (<?php echo $mediaType['total'] ?>)</label></a></li>
    <?php endforeach; ?>
                    </ul>
                </li>
            <?php endif; ?>
<?php if (count($facets['formats']) > 0): ?>   
                <li>
                    <a class="dropdown-toggle" href="#">Format</a>                                
                    <ul data-role="dropdown" <?php if (isset($facetData['format'])): ?> style="display:block" <?php endif; ?>>
                        <?php foreach ($facets['formats'] as $format): ?>
                            <li><a href="javascript://"><label for="<?php echo $format['format'] ?>"><input id='<?php echo $format['format'] ?>' <?php echo (isset($facetData['format']) && in_array($format['format'], $facetData['format'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="format[]" value="<?php echo $format['format'] ?>" /><?php echo $format['format'] ?> (<?php echo $format['total'] ?>)</label></a></li>
    <?php endforeach; ?>
                    </ul>
                </li>
<?php endif; ?>
            <li>
                <a class="dropdown-toggle" href="#">Commercial/Unique</a>                                
                <ul  data-role="dropdown" <?php if (isset($facetData['commercial'])): ?> style="display:block" <?php endif; ?>>
                    <?php foreach ($facets['commercialUnique'] as $cOrU): ?>
                        <?php if ($cOrU['commercial'] != ''): ?>  
                            <li><a href="javascript://"><label for="<?php echo $cOrU['commercial'] ?>"><input id='<?php echo $cOrU['commercial'] ?>' <?php echo (isset($facetData['commercial']) && in_array($cOrU['commercial'], $facetData['commercial'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="commercial[]" value="<?php echo $cOrU['commercial'] ?>" /><?php echo $cOrU['commercial'] ?> (<?php echo $cOrU['total'] ?>)</label></a></li>
                        <?php endif; ?>
<?php endforeach; ?>
                </ul>
            </li>
            <li>
                <a class="dropdown-toggle" href="#">Base</a>                                
                <ul  data-role="dropdown" <?php if (isset($facetData['base'])): ?> style="display:block" <?php endif; ?>>
                    <?php foreach ($facets['bases'] as $base): ?>
                        <?php if ($base['base'] != ''): ?>  
                            <li><a href="javascript://"><label for="<?php echo $base['base'] ?>"><input id='<?php echo $base['base'] ?>' <?php echo (isset($facetData['base']) && in_array($base['base'], $facetData['base'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="base[]" value="<?php echo $base['base'] ?>" /><?php echo $base['base'] ?> (<?php echo $base['total'] ?>)</label></a></li>
                        <?php endif ?>
<?php endforeach ?>
                </ul>
            </li>
            <li>
                <a class="dropdown-toggle" href="#">Collection Name</a>                                
                <ul  data-role="dropdown" <?php if (isset($facetData['collectionName'])): ?> style="display:block" <?php endif ?>>
                    <?php foreach ($facets['collectionNames'] as $collectionName): ?>
                        <?php if ($collectionName['collection_name'] != ''): ?>  
                            <li><a href="javascript://"><label for="<?php echo $collectionName['collection_name'] ?>"><input id='<?php echo $collectionName['collection_name'] ?>' <?php echo (isset($facetData['collectionName']) && in_array($collectionName['collection_name'], $facetData['collectionName'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="collectionName[]" value="<?php echo $collectionName['collection_name'] ?>" /><?php echo $collectionName['collection_name'] ?> (<?php echo $collectionName['total'] ?>)</label></a></li>
                        <?php endif ?>
<?php endforeach ?>
                </ul>
            </li>
            <li>
                <a class="dropdown-toggle" href="#">Recording Standard</a>                                
                <ul  data-role="dropdown" <?php if (isset($facetData['recordingStandard'])): ?> style="display:block" <?php endif ?>>
                    <?php foreach ($facets['recordingStandards'] as $recordingStandard): ?>
                        <?php if ($recordingStandard['recording_standard'] != ''): ?>  
                            <li><a href="javascript://"><label for="<?php echo $recordingStandard['recording_standard'] ?>"><input id='<?php echo $recordingStandard['recording_standard'] ?>' <?php echo (isset($facetData['recordingStandard']) && in_array($recordingStandard['recording_standard'], $facetData['recordingStandard'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="recordingStandard[]" value="<?php echo $recordingStandard['recording_standard'] ?>" /><?php echo $recordingStandard['recording_standard'] ?> (<?php echo $recordingStandard['total'] ?>)</label></a></li>
                        <?php endif ?>
<?php endforeach; ?>
                </ul>
            </li>
            <li>
                <a class="dropdown-toggle" href="#">Print Type</a>                                
                <ul  data-role="dropdown" <?php if (isset($facetData['printType'])): ?> style="display:block" <?php endif ?>>
                    <?php foreach ($facets['printTypes'] as $printType): ?>
                        <?php if ($printType['print_type']): ?>  
                            <li><a href="javascript://"><label for="<?php echo $printType['print_type'] ?>"><input id='<?php echo $printType['print_type'] ?>' <?php echo (isset($facetData['printType']) && in_array($printType['print_type'], $facetData['printType'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="printType[]" value="<?php echo $printType['print_type'] ?>" /><?php echo $printType['print_type'] ?> (<?php echo $printType['total'] ?>)</label></a></li>
                        <?php endif ?>
<?php endforeach ?>
                </ul>
            </li>
            <li>
                <a class="dropdown-toggle" href="#">Project Name</a>                                
                <ul  data-role="dropdown" <?php if (isset($facetData['project'])): ?> style="display:block" <?php endif ?>>
                    <?php foreach ($facets['projectNames'] as $projectName): ?>
                        <?php if ($projectName['project']): ?>  
                            <li><a href="javascript://"><label for="<?php echo $projectName['project'] ?>"><input id='<?php echo $projectName['project'] ?>' <?php echo (isset($facetData['project']) && in_array($projectName['project'], $facetData['project'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="project[]" value="<?php echo $projectName['project'] ?>" /><?php echo $projectName['project'] ?> (<?php echo $projectName['total'] ?>)</label></a></li>
                        <?php endif ?>
<?php endforeach; ?>
                </ul>
            </li>
            <li>
                <a class="dropdown-toggle" href="#">Reel Diameter</a>                                
                <ul  data-role="dropdown" <?php if (isset($facetData['reelDiameter'])): ?> style="display:block" <?php endif ?>>
                    <?php foreach ($facets['reelDiameters'] as $reelDiameter): ?>
                        <?php if ($reelDiameter['reel_diameter']): ?>  
                            <li><a href="javascript://"><label for="<?php echo $reelDiameter['reel_diameter'] ?>"><input id='<?php echo $reelDiameter['reel_diameter'] ?>' <?php echo (isset($facetData['reelDiameter']) && in_array($reelDiameter['reel_diameter'], $facetData['reelDiameter'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="reelDiameter[]" value="<?php echo $reelDiameter['reel_diameter'] ?>" /><?php echo $reelDiameter['reel_diameter'] ?> (<?php echo $reelDiameter['total'] ?>)</label></a></li>
                        <?php endif ?>
<?php endforeach ?>
                </ul>
            </li>
            <li>
                <a class="dropdown-toggle" href="#">Disc Diameter</a>                                
                <ul  data-role="dropdown" <?php if (isset($facetData['discDiameter'])): ?> style="display:block" <?php endif ?>>
                    <?php foreach ($facets['discDiameters'] as $discDiameter): ?>
                        <?php if ($discDiameter['disk_diameter'] != ''): ?>  
                            <li><a href="javascript://"><label for="<?php echo $discDiameter['disk_diameter'] ?>"><input id='<?php echo $discDiameter['disk_diameter'] ?>' <?php echo (isset($facetData['discDiameter']) && in_array($discDiameter['disk_diameter'], $facetData['discDiameter'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="discDiameter[]" value="<?php echo $discDiameter['disk_diameter'] ?>" /><?php echo $discDiameter['disk_diameter'] ?> (<?php echo $discDiameter['total'] ?>)</label></a></li>
                        <?php endif ?>
<?php endforeach; ?>
                </ul>
            </li>
            <li>
                <a class="dropdown-toggle" href="#">Acid Detection Strip</a>                                
                <ul  data-role="dropdown" <?php if (isset($facetData['acidDetection'])): ?> style="display:block" <?php endif ?>>
                    <?php foreach ($facets['acidDetection'] as $strip): ?>
                        <?php if ($strip['acid_detection']): ?>  
                            <li><a href="javascript://"><label for="<?php echo $strip['acid_detection'] ?>"><input id='<?php echo $strip['acid_detection'] ?>' <?php echo (isset($facetData['acidDetection']) && in_array($strip['acid_detection'], $facetData['acidDetection'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="acidDetection[]" value="<?php echo $strip['acid_detection'] ?>" /><?php echo $strip['acid_detection'] ?> (<?php echo $strip['total'] ?>)</label></a></li>
                        <?php endif ?>
<?php endforeach; ?>
                </ul>
            </li>
            <li class="chekBoxFacet">
                <span id="is_review_check" style="cursor: default;">
                    <?php $review_check = 0 ?>
                    <?php if (isset($facetData['is_review'])): ?>
                        <?php $review_check = $facetData['is_review'] ?>
<?php endif; ?>    
                    <input type="hidden" id="is_review_check_state" name="is_review_check" value="<?php echo $review_check ?>" <?php echo (isset($facetData['is_review']) && $facetData['is_review'] == $review_check) ? 'checked="checked"' : '' ?> /> 
                </span>
                Review
            </li>  
        </ul>
    </form>
</nav>
<input type="hidden" value="" name="parent_facet" id="parent_facet" />
<input type="hidden" value="" name="total_checked" id="total_checked"/>
<script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/tristate-0.9.2.js') ?>"></script> 
<script type="text/javascript">
    initTriStateCheckBox('is_review_check', 'is_review_check_state', true);
    $(window).load(function () {
        var facets = new Records();
        facets.setAjaxSource('<?php echo $view['router']->generate('record_dataTable') ?>');
        facets.setPageUrl('<?php echo $view['router']->generate('record_list') ?>');
        facets.bindEvents();
    });
</script>