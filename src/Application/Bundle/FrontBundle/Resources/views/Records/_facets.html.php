<form id='formSearch' name='formSearch' method='post' onsubmit="return false;">
    <nav id="facet_sidebar" class="sidebar light">

        <ul>
            <?php
            $facetData = $app->getSession()->get('facetData');
            $orgData = $app->getSession()->get('organization');
            $projectData = $app->getSession()->get('projectFacet');

            function name_slug($name) {
                $random = rand(0, 1000365);
                $name = str_replace("/", "", trim($name));
                $name = str_replace("?", "q", trim($name));
                $name = str_replace(" ", "", trim($name));
                $name = str_replace("(", "", trim($name));
                $name = str_replace(")", "", trim($name));
                $name = str_replace(",", "", trim($name));
                $name = str_replace(".", "", trim($name));
                $name = str_replace(";", "", trim($name));
                $name = str_replace(":", "", trim($name));
                $name = str_replace("&", "", trim($name));
                $name = strtolower($name);
                return $name;
            }
            ?>
            <li class="title">Filters</li>
            <?php if ($facetData): ?>
                <li>

                    <?php
                    if (isset($facetData['facet_keyword_search']) && count(json_decode($facetData['facet_keyword_search'])) > 0) {
                        ?>
                        <div id="keyword_main" class="chekBoxFacet">
                            <?php
                            $session_keywords = $view['myViewHelper']->sortByOneKey(json_decode($facetData['facet_keyword_search']), 'type');
                            $types = array();
                            foreach ($session_keywords as $key => $value) {
                                if (!in_array($value->type, $types)) {
                                    $types[] = $value->type;
                                    ?>
                                    <div class="filter-fileds"><b>Keyword: <?php echo ucfirst(str_replace("_", ' ', $value->type)); ?></b></div>
                                    <?php
                                }
                                $id = time() . rand(0, 1000);
                                ?>
                                <div class="btn-img" id="facet_keyword_<?php echo $id; ?>" ><span class="search_keys"><?php echo html_entity_decode($value->value); ?></span><i class="icon-cancel deleteKeyword" data-index="<?php echo $key; ?>" style="float: right;cursor: pointer;" data-elementId="<?php echo 'keyword_' . name_slug($value->value); ?>" data-type="keyword"></i></div>
                            <?php }
                            ?>
                        </div>

                        <div class="clearfix"></div>

                    <?php } ?>
                    <?php
                    if (isset($orgData) && $orgData != '') {
                        ?>
                        <div id="organizationName_main" class="chekBoxFacet">
                            <div class="filter-fileds"><b>Organization</b></div>
                            <?php
                            foreach ($orgData as $index => $value) {
                                $id = time() . rand(0, 1000);
                                ?>
                                <div class="btn-img" id="facet_organization_<?php echo $index; ?>" ><span class="search_keys"><?php echo html_entity_decode($value); ?></span><i class="icon-cancel delFilter" style="float: right;cursor: pointer;" data-elementId="<?php echo $index . '_organization_name_' . name_slug($value); ?>" data-type="organization_name"></i></div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>

                    <?php } ?>
                    <?php
                    if (isset($projectData) && $projectData != '') {
                        ?>
                        <div id="mediaType_main" class="chekBoxFacet">
                            <div class="filter-fileds"><b>Project</b></div>
                            <?php
                            foreach ($projectData as $id => $value) {
//                                $id = time() . rand(0, 1000);
                                ?>
                                <div class="btn-img" id="facet_project_<?php echo $id; ?>" ><span class="search_keys"><?php echo html_entity_decode($value); ?></span><i class="icon-cancel delFilter" style="float: right;cursor: pointer;" data-elementId="<?php echo $id . '_project_' . name_slug($value); ?>" data-type="project"></i></div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                    <?php } ?>
                    <?php
                    if (isset($facetData['parent_collection']) && $facetData['parent_collection'] != '') {
                        ?>
                        <div id="mediaType_main" class="chekBoxFacet">
                            <div class="filter-fileds"><b>Collection Classification</b></div>
                            <?php
                            foreach ($facetData['parent_collection'] as $value) {
                                $id = time() . rand(0, 1000);
                                ?>
                                <div class="btn-img" id="facet_media_<?php echo $id; ?>" ><span class="search_keys"><?php echo html_entity_decode($value); ?></span><i class="icon-cancel delFilter" style="float: right;cursor: pointer;" data-elementId="<?php echo 'parentCollection_' . name_slug($value); ?>" data-type="parentCollection"></i></div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                    <?php } ?>
                    <?php
                    if (isset($facetData['collection_name']) && $facetData['collection_name'] != '') {
                        ?>
                        <div id="mediaType_main" class="chekBoxFacet">
                            <div class="filter-fileds"><b>Collection Name</b></div>
                            <?php
                            foreach ($facetData['collection_name'] as $value) {
                                $id = time() . rand(0, 1000);
                                ?>
                                <div class="btn-img" id="facet_media_<?php echo $id; ?>" ><span class="search_keys"><?php echo html_entity_decode($value); ?></span><i class="icon-cancel delFilter" style="float: right;cursor: pointer;" data-elementId="<?php echo 'collectionName_' . name_slug($value); ?>" data-type="collectionName"></i></div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                    <?php } ?>
                    <?php
                    if (isset($facetData['mediaType']) && $facetData['mediaType'] != '') {
                        ?>
                        <div id="mediaType_main" class="chekBoxFacet">
                            <div class="filter-fileds"><b>Media Type</b></div>
                            <?php
                            foreach ($facetData['mediaType'] as $value) {
                                $id = time() . rand(0, 1000);
                                ?>
                                <div class="btn-img" id="facet_media_<?php echo $id; ?>" ><span class="search_keys"><?php echo html_entity_decode($value); ?></span><i class="icon-cancel delFilter" style="float: right;cursor: pointer;" data-elementId="<?php echo 'mediaType_' . name_slug($value); ?>" data-type="mediaType"></i></div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>

                    <?php } ?>

                    <?php
                    if (isset($facetData['format']) && $facetData['format'] != '') {
                        ?>
                        <div id="mediaType_main" class="chekBoxFacet">
                            <div class="filter-fileds"><b>Format</b></div>
                            <?php
                            foreach ($facetData['format'] as $value) {
                                $id = time() . rand(0, 1000);
                                ?>
                                <div class="btn-img" id="facet_media_<?php echo $id; ?>" ><span class="search_keys"><?php echo html_entity_decode($value); ?></span><i class="icon-cancel delFilter" style="float: right;cursor: pointer;" data-elementId="<?php echo 'format_' . name_slug($value); ?>" data-type="format"></i></div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                    <?php } ?>
                    <?php
                    if (isset($facetData['commercial']) && $facetData['commercial'] != '') {
                        ?>
                        <div id="mediaType_main" class="chekBoxFacet">
                            <div class="filter-fileds"><b>Commercial / Unique</b></div>
                            <?php
                            foreach ($facetData['commercial'] as $value) {
                                $id = time() . rand(0, 1000);
                                ?>
                                <div class="btn-img" id="facet_media_<?php echo $id; ?>" ><span class="search_keys"><?php echo html_entity_decode($value); ?></span><i class="icon-cancel delFilter" style="float: right;cursor: pointer;" data-elementId="<?php echo 'commercial_' . name_slug($value); ?>" data-type="commercial"></i></div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                    <?php } ?>
                    <?php
                    if (isset($facetData['base']) && $facetData['base'] != '') {
                        ?>
                        <div id="mediaType_main" class="chekBoxFacet">
                            <div class="filter-fileds"><b>Base</b></div>
                            <?php
                            foreach ($facetData['base'] as $value) {
                                $id = time() . rand(0, 1000);
                                ?>
                                <div class="btn-img" id="facet_media_<?php echo $id; ?>" ><span class="search_keys"><?php echo html_entity_decode($value); ?></span><i class="icon-cancel delFilter" style="float: right;cursor: pointer;" data-elementId="<?php echo 'base_' . name_slug($value); ?>" data-type="base"></i></div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                    <?php } ?>
                    <?php
                    if (isset($facetData['printType']) && $facetData['printType'] != '') {
                        ?>
                        <div id="mediaType_main" class="chekBoxFacet">
                            <div class="filter-fileds"><b>Print Type</b></div>
                            <?php
                            foreach ($facetData['printType'] as $value) {
                                $id = time() . rand(0, 1000);
                                ?>
                                <div class="btn-img" id="facet_media_<?php echo $id; ?>" ><span class="search_keys"><?php echo html_entity_decode($value); ?></span><i class="icon-cancel delFilter" style="float: right;cursor: pointer;" data-elementId="<?php echo 'printType_' . name_slug($value); ?>" data-type="printType"></i></div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                    <?php } ?>
                    <?php
                    if (isset($facetData['acidDetection']) && $facetData['acidDetection'] != '') {
                        ?>
                        <div id="mediaType_main" class="chekBoxFacet">
                            <div class="filter-fileds"><b>Acid Detection</b></div>
                            <?php
                            foreach ($facetData['acidDetection'] as $value) {
                                $id = time() . rand(0, 1000);
                                ?>
                                <div class="btn-img" id="facet_media_<?php echo $id; ?>" ><span class="search_keys"><?php echo html_entity_decode($value); ?></span><i class="icon-cancel delFilter" style="float: right;cursor: pointer;" data-elementId="<?php echo 'acidDetection_' . name_slug($value); ?>" data-type="acidDetection"></i></div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                    <?php } ?>
                    <?php
                    if (isset($facetData['reelDiameter']) && $facetData['reelDiameter'] != '') {
                        ?>
                        <div id="mediaType_main" class="chekBoxFacet">
                            <div class="filter-fileds"><b>Reel Diameter</b></div>
                            <?php
                            foreach ($facetData['reelDiameter'] as $value) {
                                $id = time() . rand(0, 1000);
                                ?>
                                <div class="btn-img" id="facet_media_<?php echo $id; ?>" ><span class="search_keys"><?php echo html_entity_decode($value); ?></span><i class="icon-cancel delFilter" style="float: right;cursor: pointer;" data-elementId="<?php echo 'reelDiameter_' . name_slug($value); ?>" data-type="reelDiameter"></i></div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                    <?php } ?>
                    <?php
                    if (isset($facetData['discDiameter']) && $facetData['discDiameter'] != '') {
                        ?>
                        <div id="mediaType_main" class="chekBoxFacet">
                            <div class="filter-fileds"><b>Disk Diameter</b></div>
                            <?php
                            foreach ($facetData['discDiameter'] as $value) {
                                $id = time() . rand(0, 1000);
                                ?>
                                <div class="btn-img" id="facet_media_<?php echo $id; ?>" ><span class="search_keys"><?php echo html_entity_decode($value); ?></span><i class="icon-cancel delFilter" style="float: right;cursor: pointer;" data-elementId="<?php echo 'discDiameter_' . name_slug($value); ?>" data-type="discDiameter"></i></div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                    <?php } ?>
                    <?php
                    if (isset($facetData['recordingStandard']) && $facetData['recordingStandard'] != '') {
                        ?>
                        <div id="mediaType_main" class="chekBoxFacet">
                            <div class="filter-fileds"><b>Recording Standard</b></div>
                            <?php
                            foreach ($facetData['recordingStandard'] as $value) {
                                $id = time() . rand(0, 1000);
                                ?>
                                <div class="btn-img" id="facet_media_<?php echo $id; ?>" ><span class="search_keys"><?php echo html_entity_decode($value); ?></span><i class="icon-cancel delFilter" style="float: right;cursor: pointer;" data-elementId="<?php echo 'recordingStandard_' . name_slug($value); ?>" data-type="recordingStandard"></i></div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                    <?php } ?>  
                        <?php if (isset($facetData['accessLevel']) && $facetData['accessLevel'] != '') {
                        ?>
                        <div id="mediaType_main" class="chekBoxFacet">
                            <div class="filter-fileds"><b>Access Level</b></div>
                            <?php
                            foreach ($facetData['accessLevel'] as $value) {
                                $id = time() . rand(0, 1000);
                                ?>
                                <div class="btn-img" id="facet_media_<?php echo $id; ?>" ><span class="search_keys"><?php echo html_entity_decode($value); ?></span><i class="icon-cancel delFilter" style="float: right;cursor: pointer;" data-elementId="<?php echo 'accessLevel_' . name_slug($value); ?>" data-type="accessLevel"></i></div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                    <?php } ?>    
                    <div class="clearfix"></div>
                    <div><input type="button" value="Reset" id="reset_all" name="reset_all"  style="display: none;" class="button" /></div>
                </li>
            <?php endif; ?>
            <li>
                <a class="dropdown-toggle" href="#">Keyword</a>
                <div data-role="dropdown" class="chekBoxFacet">
                    <div data-role="input-control" class="input-control text">
                        <input type="text" id="keywordSearch" value="" />
                    </div>
                    <div class="button-dropdown place-left">
                        <button class="dropdown-toggle" type="button" id="limit_field_text">Search</button>
                        <ul class="dropdown-menu" data-role="dropdown" id="keyword_menu">
                            <li><a href="javascript://;" class="customToken" data-fieldName="Title"  data-columnName="title" style="font-size: 12px!important;">Title</a></li>
                            <li><a href="javascript://;" class="customToken" data-fieldName="Description"  data-columnName="description" style="font-size: 12px!important;">Description</a></li>
                            <li><a href="javascript://;" class="customToken" data-fieldName="Collection Name"  data-columnName="collection_name" style="font-size: 12px!important;">Collection Name</a></li>
                            <li><a href="javascript://;" class="customToken" data-fieldName="Creation Date"  data-columnName="creation_date" style="font-size: 12px!important;">Creation Date</a></li>
                            <li><a href="javascript://;" class="customToken" data-fieldName="Content Date"  data-columnName="content_date" style="font-size: 12px!important;">Content Date</a></li>
                            <li><a href="javascript://;" class="customToken" data-fieldName="Genre Terms"  data-columnName="genre_terms" style="font-size: 12px!important;">Genre Terms</a></li>
                            <li><a href="javascript://;" class="customToken" data-fieldName="Contributor"  data-columnName="contributor" style="font-size: 12px!important;">Contributor</a></li>
                            <li><a href="javascript://;" class="customToken" data-fieldName="Unique ID"  data-columnName="unique_id" style="font-size: 12px!important;">Unique ID</a></li>
                            <li><a href="javascript://;" class="customToken" data-fieldName="General Note"  data-columnName="general_note" style="font-size: 12px!important;">General Note</a></li>
                            <li><a href="javascript://;" class="customToken" data-fieldName="Condition Note"  data-columnName="condition_note" style="font-size: 12px!important;">Condition Note</a></li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                    <div class="button primary" id="addKeyword">Add Keyword</div>
                </div>
            </li>
            <?php if ($view['security']->isGranted('ROLE_SUPER_ADMIN')): ?>
                <?php if (count($facets['organizationNames']) > 0): ?>
                    <li>
                        <a class="dropdown-toggle" href="#">Organizations</a>
                        <ul data-role="dropdown" <?php echo isset($facetData['organization_name']) ? 'style="display:block"' : 'style="display:none"'; ?>>
                            <div class="controls">
                                <div class="input-append">
                                    <input style="  margin-left: 10px; height: 25px; width: 199px !important;" id="org_filter" name="org_filter" type="text" value="" >
                                </div>
                            </div>
                            <?php foreach ($facets['organizationNames'] as $key => $mediaType): ?>
                                <?php if ($key < 5) { ?>
                                    <li><a href="javascript://"><label for="<?php echo $mediaType['organization_id'] . '_organization_name_' . name_slug($mediaType['organization_name']) ?>"><input id='<?php echo $mediaType['organization_id'] . '_organization_name_' . name_slug($mediaType['organization_name']) ?>' <?php echo (isset($facetData['organization_name']) && in_array($mediaType['organization_id'], $facetData['organization_name'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="organization_name[]" value="<?php echo $mediaType['organization_id'] ?>" style="float:left; width:20px"/><div class="orgs" style="margin-left: 25px;color: #696969;"><?php echo $mediaType['organization_name'] ?> (<?php echo $mediaType['total'] ?>)</div></label></a></li>
                                <?php } else if ($key == 5) { ?>                        
                                    <a class="org_ml" onclick="more_less('org_ml')" style="text-decoration: underline;">
                                        More
                                        <b class="caret"></b>
                                    </a>
                                    <div id="org_ml" style="display:none;">
                                        <li><a href="javascript://"><label for="<?php echo $mediaType['organization_id'] . '_organization_name_' . name_slug($mediaType['organization_name']) ?>"><input id='<?php echo $mediaType['organization_id'] . '_organization_name_' . name_slug($mediaType['organization_name']) ?>' <?php echo (isset($facetData['organization_name']) && in_array($mediaType['organization_id'], $facetData['organization_name'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="organization_name[]" value="<?php echo $mediaType['organization_id'] ?>" style="float:left; width:20px"/><div class="orgs" style="margin-left: 25px;color: #696969;"><?php echo $mediaType['organization_name'] ?> (<?php echo $mediaType['total'] ?>)</div></label></a></li>
                                        <?php
                                    } else {
                                        ?>
                                        <li><a href="javascript://"><label for="<?php echo $mediaType['organization_id'] . '_organization_name_' . name_slug($mediaType['organization_name']) ?>"><input id='<?php echo $mediaType['organization_id'] . '_organization_name_' . name_slug($mediaType['organization_name']) ?>' <?php echo (isset($facetData['organization_name']) && in_array($mediaType['organization_id'], $facetData['organization_name'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="organization_name[]" value="<?php echo $mediaType['organization_id'] ?>" style="float:left; width:20px"/><div class="orgs" style="margin-left: 25px;color: #696969;"><?php echo $mediaType['organization_name'] ?> (<?php echo $mediaType['total'] ?>)</div></label></a></li>
                                    <?php }
                                    ?>                       
                                    <?php
                                endforeach;
                                if (count($facets['organizationNames']) > 5) {
                                    ?>
                                    <a class="format_ml" onclick="more_less('org_ml')" style="display:none;text-decoration: underline;">Less</a>
                                </div>
                            <?php } ?>
                        </ul> 
                    </li>
                    <?php
                endif;
            endif;
            ?>
                    <?php if (count($facets['projectNames']) > 0): ?>
                <li>
                    <a class="dropdown-toggle" href="#">Project Name</a>
                    <ul  data-role="dropdown" <?php if (isset($facetData['project'])): ?> style="display:block" <?php endif ?>>
                        <div class="controls">
                            <div class="input-append">
                                <input style="  margin-left: 10px; height: 25px; width: 199px !important;" id="project_filter" name="project_filter" type="text" value="" >
                            </div>
                        </div>
                        <?php foreach ($facets['projectNames'] as $key => $projectName): ?>
                            <?php if ($projectName['project']): ?>
                                <?php if ($key < 5) { ?>       
                                    <li><a href="javascript://"><label for="<?php echo $projectName['project_id'] . '_project_' . str_replace(' ', '_', strtolower($projectName['project'])) ?>"><input id='<?php echo $projectName['project_id'] . '_project_' . name_slug($projectName['project']) ?>' <?php echo (isset($facetData['project']) && in_array($projectName['project_id'], $facetData['project'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="project[]" value="<?php echo $projectName['project_id'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;" class="projects"><?php echo $projectName['project'] ?> (<?php echo $projectName['total'] ?>)</div></label></a></li>
                                <?php } else if ($key == 5) { ?>                        
                                    <a class="project_ml" onclick="more_less('project_ml')" style="text-decoration: underline;">
                                        More
                                        <b class="caret"></b>
                                    </a>
                                    <div id="project_ml" style="display:none;">
                                        <li><a href="javascript://"><label for="<?php echo $projectName['project_id'] . '_project_' . str_replace(' ', '_', strtolower($projectName['project'])) ?>"><input id='<?php echo $projectName['project_id'] . '_project_' . name_slug($projectName['project']) ?>' <?php echo (isset($facetData['project']) && in_array($projectName['project_id'], $facetData['project'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="project[]" value="<?php echo $projectName['project_id'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;" class="projects"><?php echo $projectName['project'] ?> (<?php echo $projectName['total'] ?>)</div></label></a></li>
                                        <?php
                                    } else {
                                        ?>
                                        <li><a href="javascript://"><label for="<?php echo $projectName['project_id'] . '_project_' . str_replace(' ', '_', strtolower($projectName['project'])) ?>"><input id='<?php echo $projectName['project_id'] . '_project_' . name_slug($projectName['project']) ?>' <?php echo (isset($facetData['project']) && in_array($projectName['project_id'], $facetData['project'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="project[]" value="<?php echo $projectName['project_id'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;" class="projects"><?php echo $projectName['project'] ?> (<?php echo $projectName['total'] ?>)</div></label></a></li>
                                    <?php } ?>
                                <?php endif ?>
                            <?php endforeach; ?>
                            <?php
                            if (count($facets['projectNames']) > 5) {
                                ?>
                                <a class="project_ml" onclick="more_less('project_ml')" style="display:none;text-decoration: underline;">Less</a>
                            </div>
                        <?php } ?>

                    </ul>
                </li>
            <?php endif; ?>
                <?php if (count($facets['parentCollection']) > 0): ?>
                <li>
                    <a class="dropdown-toggle" href="#">Collection Classification</a>
                    <ul  data-role="dropdown" <?php if (isset($facetData['parent_collection'])): ?> style="display:block" <?php endif ?>>
                        <div class="controls">
                            <div class="input-append">
                                <input style="  margin-left: 10px; height: 25px; width: 199px !important;" id="parent_collection_filter" name="parent_collection_filter" type="text" value="" >
                            </div>
                        </div>
                        <?php foreach ($facets['parentCollection'] as $key => $collectionName): ?>
                            <?php if ($collectionName['parent_collection'] != ''): ?>
                                <?php if ($key < 5) { ?>  
                                    <li><a href="javascript://"><label for="<?php echo 'parent_collection_' . str_replace(' ', '_', strtolower($collectionName['parent_collection'])) ?>"><input id='<?php echo 'parentCollection_' . name_slug($collectionName['parent_collection']) ?>' <?php echo (isset($facetData['parent_collection']) && in_array($collectionName['parent_collection'], $facetData['parent_collection'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="parent_collection[]" value="<?php echo $collectionName['parent_collection'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;" class="parent_collection"><?php echo $collectionName['parent_collection'] ?> (<?php echo $collectionName['total'] ?>)</div></label></a></li>
                                <?php } else if ($key == 5) { ?>                        
                                    <a class="parent_collection_ml" onclick="more_less('parent_collection_ml')" style="text-decoration: underline;">
                                        More
                                        <b class="caret"></b>
                                    </a>
                                    <div id="parent_collection_ml" style="display:none;">
                                        <li><a href="javascript://"><label for="<?php echo 'parentCollection_' . str_replace(' ', '_', strtolower($collectionName['parent_collection'])) ?>"><input id='<?php echo 'parentCollection_' . name_slug($collectionName['parent_collection']) ?>' <?php echo (isset($facetData['parent_collection']) && in_array($collectionName['parent_collection'], $facetData['parent_collection'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="parent_collection[]" value="<?php echo $collectionName['parent_collection'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;" class="parent_collection"><?php echo $collectionName['parent_collection'] ?> (<?php echo $collectionName['total'] ?>)</div></label></a></li>
                                        <?php
                                    } else {
                                        ?>
                                        <li><a href="javascript://"><label for="<?php echo 'parentCollection_' . str_replace(' ', '_', strtolower($collectionName['parent_collection'])) ?>"><input id='<?php echo 'parentCollection_' . name_slug($collectionName['parent_collection']) ?>' <?php echo (isset($facetData['parent_collection']) && in_array($collectionName['parent_collection'], $facetData['parent_collection'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="parent_collection[]" value="<?php echo $collectionName['parent_collection'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;" class="parent_collection"><?php echo $collectionName['parent_collection'] ?> (<?php echo $collectionName['total'] ?>)</div></label></a></li>
                                    <?php } ?>
                                <?php endif ?>
                            <?php endforeach; ?>
                            <?php
                            if (count($facets['parentCollection']) > 5) {
                                ?>
                                <a class="parent_collection_ml" onclick="more_less('parent_collection_ml')" style="display:none;text-decoration: underline;">Less</a>
                            </div>
                        <?php } ?>

                    </ul>
                </li>
            <?php endif; ?>
            <?php if (count($facets['collectionNames']) > 0): ?>
                <li>
                    <a class="dropdown-toggle" href="#">Collection Name</a>
                    <ul  data-role="dropdown" <?php if (isset($facetData['collection_name'])): ?> style="display:block" <?php endif ?>>
                        <div class="controls">
                            <div class="input-append">
                                <input style="  margin-left: 10px; height: 25px; width: 199px !important;" id="collection_filter" name="collection_filter" type="text" value="" >
                            </div>
                        </div>
                        <?php foreach ($facets['collectionNames'] as $key => $collectionName): ?>
                            <?php if ($collectionName['collection_name'] != ''): ?>
                                <?php if ($key < 5) { ?>  
                                    <li><a href="javascript://"><label for="<?php echo 'collectionName_' . str_replace(' ', '_', strtolower($collectionName['collection_name'])) ?>"><input id='<?php echo 'collectionName_' . name_slug($collectionName['collection_name']) ?>' <?php echo (isset($facetData['collection_name']) && in_array($collectionName['collection_name'], $facetData['collection_name'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="collection_name[]" value="<?php echo $collectionName['collection_name'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;" class="collections"><?php echo $collectionName['collection_name'] ?> (<?php echo $collectionName['total'] ?>)</div></label></a></li>
                                <?php } else if ($key == 5) { ?>                        
                                    <a class="collection_ml" onclick="more_less('collection_ml')" style="text-decoration: underline;">
                                        More
                                        <b class="caret"></b>
                                    </a>
                                    <div id="collection_ml" style="display:none;">
                                        <li><a href="javascript://"><label for="<?php echo 'collectionName_' . str_replace(' ', '_', strtolower($collectionName['collection_name'])) ?>"><input id='<?php echo 'collectionName_' . name_slug($collectionName['collection_name']) ?>' <?php echo (isset($facetData['collection_name']) && in_array($collectionName['collection_name'], $facetData['collection_name'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="collection_name[]" value="<?php echo $collectionName['collection_name'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;" class="collections"><?php echo $collectionName['collection_name'] ?> (<?php echo $collectionName['total'] ?>)</div></label></a></li>
                                        <?php
                                    } else {
                                        ?>
                                        <li><a href="javascript://"><label for="<?php echo 'collectionName_' . str_replace(' ', '_', strtolower($collectionName['collection_name'])) ?>"><input id='<?php echo 'collectionName_' . name_slug($collectionName['collection_name']) ?>' <?php echo (isset($facetData['collection_name']) && in_array($collectionName['collection_name'], $facetData['collection_name'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="collection_name[]" value="<?php echo $collectionName['collection_name'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;" class="collections"><?php echo $collectionName['collection_name'] ?> (<?php echo $collectionName['total'] ?>)</div></label></a></li>
                                    <?php } ?>
                                <?php endif ?>
                            <?php endforeach; ?>
                            <?php
                            if (count($facets['collectionNames']) > 5) {
                                ?>
                                <a class="collection_ml" onclick="more_less('collection_ml')" style="display:none;text-decoration: underline;">Less</a>
                            </div>
                        <?php } ?>

                    </ul>
                </li>
            <?php endif; ?>
            <?php if (count($facets['mediaType']) > 0): ?>
                <li>
                    <a class="dropdown-toggle" href="#">Media Type</a>
                    <ul data-role="dropdown" <?php echo isset($facetData['mediaType']) ? 'style="display:block"' : 'style="display:none"'; ?>>
                        <?php foreach ($facets['mediaType'] as $mediaType): ?>
                            <li><a href="javascript://"><label for="<?php echo 'mediaType_' . str_replace(' ', '_', strtolower($mediaType['media_type'])) ?>"><input id='<?php echo 'mediaType_' . str_replace(' ', '_', strtolower($mediaType['media_type'])) ?>' <?php echo (isset($facetData['mediaType']) && in_array($mediaType['media_type'], $facetData['mediaType'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="mediaType[]" value="<?php echo $mediaType['media_type'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;"><?php echo $mediaType['media_type'] ?> (<?php echo $mediaType['total'] ?>)</div></label></a></li>
                        <?php endforeach; ?>
                    </ul> 
                </li>
            <?php endif; ?>
            
            <?php if (count($facets['formats']) > 0): ?>
                <li>
                    <a class="dropdown-toggle" href="#">Format</a>

                    <ul data-role="dropdown" <?php if (isset($facetData['format'])): ?> style="display:block" <?php endif; ?>>
                        <div class="controls">
                            <div class="input-append">
                                <input style="  margin-left: 10px; height: 25px; width: 199px !important;" id="formt_filter" name="formt_filter" type="text" value="" >
                            </div>
                        </div>
                        <?php foreach ($facets['formats'] as $key => $format): ?>
                            <?php if ($key < 5) { ?>
                                <li><a href="javascript://"><label for="<?php echo 'format_' . str_replace(' ', '_', strtolower($format['format'])) ?>"><input id='<?php echo 'format_' . name_slug($format['format']) ?>' <?php echo (isset($facetData['format']) && in_array($format['format'], $facetData['format'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="format[]" value="<?php echo $format['format'] ?>" style="float:left; width:20px"/><div class="frmts" style="margin-left: 25px;color: #696969;"><?php echo $format['format'] ?> (<?php echo $format['total'] ?>)</div></label></a></li>
                            <?php } else if ($key == 5) { ?>                        
                                <a class="format_ml" onclick="more_less('format_ml')" style="text-decoration: underline;">
                                    More
                                    <b class="caret"></b>
                                </a>
                                <div id="format_ml" style="display:none;">
                                    <li><a href="javascript://"><label for="<?php echo 'format_' . str_replace(' ', '_', strtolower($format['format'])) ?>"><input id='<?php echo 'format_' . name_slug($format['format']) ?>' <?php echo (isset($facetData['format']) && in_array($format['format'], $facetData['format'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="format[]" value="<?php echo $format['format'] ?>" style="float:left; width:20px"/><div class="frmts" style="margin-left: 25px;color: #696969;"><?php echo $format['format'] ?> (<?php echo $format['total'] ?>)</div></label></a></li>
                                    <?php
                                } else {
                                    ?>
                                    <li><a href="javascript://"><label for="<?php echo 'format_' . str_replace(' ', '_', strtolower($format['format'])) ?>"><input id='<?php echo 'format_' . name_slug($format['format']) ?>' <?php echo (isset($facetData['format']) && in_array($format['format'], $facetData['format'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="format[]" value="<?php echo $format['format'] ?>" style="float:left; width:20px"/><div class="frmts" style="margin-left: 25px;color: #696969;"><?php echo $format['format'] ?> (<?php echo $format['total'] ?>)</div></label></a></li>
                                <?php }
                                ?>
                            <?php endforeach; ?>
                            <?php
                            if (count($facets['formats']) > 5) {
                                ?>
                                <a class="format_ml" onclick="more_less('format_ml')" style="display:none;text-decoration: underline;">Less</a>
                            </div>
                        <?php } ?>
                    </ul>
                </li>
            <?php endif; ?>
                <li class="chekBoxFacet">
                <span id="is_reformatting_priority_check" style="cursor: default;">
                    <?php
                    $reformatting_priority_check = 0;
                    if (isset($facetData['is_reformatting_priority_check'])):

                        $reformatting_priority_check = $facetData['is_reformatting_priority_check'];
                    endif;
                    ?>
                    <input type="hidden" id="is_reformatting_priority_check_state" name="is_reformatting_priority_check" value="<?php echo $reformatting_priority_check ?>"  />
                </span>
                Reformatting Priority
            </li>
            <li class="chekBoxFacet">
                <span id="is_digitized_check" style="cursor: default;">
                    <?php
                    $digitized = 0;
                    if (isset($facetData['is_digitized_check'])):

                        $digitized = $facetData['is_digitized_check'];
                    endif;
                    ?>
                    <input type="hidden" id="is_digitized_check_state" name="is_digitized_check" value="<?php echo $digitized ?>"  />
                </span>
                Digitized
            </li>
            <li class="chekBoxFacet">
                <span id="is_transcription_check" style="cursor: default;">
                    <?php
                    $transcription = 0;
                    if (isset($facetData['is_transcription_check'])):

                        $transcription = $facetData['is_transcription_check'];
                    endif;
                    ?>
                    <input type="hidden" id="is_transcription_check_state" name="is_transcription_check" value="<?php echo $transcription ?>"  />
                </span>
                Transcription 
            </li>
            <li class="chekBoxFacet">
                <span id="has_images_check" style="cursor: default;">
                    <?php
                    $digitized = 0;
                    if (isset($facetData['has_images_check'])):

                        $digitized = $facetData['has_images_check'];
                    endif;
                    ?>
                    <input type="hidden" id="has_images_check_state" name="has_images_check" value="<?php echo $digitized ?>"  />
                </span>
                Has Images
            </li>
            <?php if (count($facets['commercialUnique']) > 0): ?>
                <li>
                    <a class="dropdown-toggle" href="#">Commercial/Unique</a>
                    <ul  data-role="dropdown" <?php if (isset($facetData['commercial'])): ?> style="display:block" <?php endif; ?>>
                        <?php foreach ($facets['commercialUnique'] as $cOrU): ?>
                            <?php if ($cOrU['commercial'] != ''): ?>
                                <li><a href="javascript://"><label for="<?php echo 'commercial_' . str_replace(' ', '_', strtolower($cOrU['commercial'])) ?>"><input id='<?php echo 'commercial_' . name_slug($cOrU['commercial']) ?>' <?php echo (isset($facetData['commercial']) && in_array($cOrU['commercial'], $facetData['commercial'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="commercial[]" value="<?php echo $cOrU['commercial'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;"><?php echo $cOrU['commercial'] ?> (<?php echo $cOrU['total'] ?>)</div></label></a></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if (count($facets['bases']) > 0): ?>
                <li>
                    <a class="dropdown-toggle" href="#">Base</a>
                    <ul  data-role="dropdown" <?php if (isset($facetData['base'])): ?> style="display:block" <?php endif; ?>>
                        <?php foreach ($facets['bases'] as $base): ?>
                            <?php if ($base['base'] != ''): ?>
                                <li><a href="javascript://"><label for="<?php echo 'base_' . str_replace(' ', '_', strtolower($base['base'])) ?>"><input id='<?php echo 'base_' . name_slug($base['base']) ?>' <?php echo (isset($facetData['base']) && in_array($base['base'], $facetData['base'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="base[]" value="<?php echo $base['base'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;"><?php echo $base['base'] ?> (<?php echo $base['total'] ?>)</div></label></a></li>
                            <?php endif ?>
                        <?php endforeach ?>
                    </ul>
                </li>
            <?php endif; ?>
            
 <?php if (count($facets['printTypes']) > 0): ?>
                <li>
                    <a class="dropdown-toggle" href="#">Print Type</a>
                    <ul  data-role="dropdown" <?php if (isset($facetData['printType'])): ?> style="display:block" <?php endif ?>>
                        <?php foreach ($facets['printTypes'] as $printType): ?>
                            <?php if ($printType['print_type']): ?>
                                <li><a href="javascript://"><label for="<?php echo 'printType_' . str_replace(' ', '_', strtolower($printType['print_type'])) ?>"><input id='<?php echo 'printType_' . name_slug($printType['print_type']) ?>' <?php echo (isset($facetData['printType']) && in_array($printType['print_type'], $facetData['printType'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="printType[]" value="<?php echo $printType['print_type'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;"><?php echo $printType['print_type'] ?> (<?php echo $printType['total'] ?>)</div></label></a></li>
                            <?php endif ?>
                        <?php endforeach ?>
                    </ul>
                </li>
            <?php endif; ?>
<?php if (count($facets['acidDetection']) > 0): ?>
                <li>
                    <a class="dropdown-toggle" href="#">Acid Detection Strip</a>
                    <ul  data-role="dropdown" <?php if (isset($facetData['acidDetection'])): ?> style="display:block" <?php endif ?>>
                        <?php foreach ($facets['acidDetection'] as $strip): ?>
                            <?php if ($strip['acid_detection']): ?>
                                <li><a href="javascript://"><label for="<?php echo 'acidDetection_' . str_replace(' ', '_', strtolower($strip['acid_detection'])) ?>"><input id='<?php echo 'acidDetection_' . name_slug($strip['acid_detection']) ?>' <?php echo (isset($facetData['acidDetection']) && in_array($strip['acid_detection'], $facetData['acidDetection'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="acidDetection[]" value="<?php echo $strip['acid_detection'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;"><?php echo $strip['acid_detection'] ?> (<?php echo $strip['total'] ?>)</div></label></a></li>
                            <?php endif ?>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endif; ?>
                <?php if (count($facets['reelDiameters']) > 0): ?>
                <li>
                    <a class="dropdown-toggle" href="#">Reel Diameter</a>
                    <ul  data-role="dropdown" <?php if (isset($facetData['reelDiameter'])): ?> style="display:block" <?php endif ?>>
                        <?php foreach ($facets['reelDiameters'] as $reelDiameter): ?>
                            <?php if ($reelDiameter['reel_diameter']): ?>
                                <li><a href="javascript://"><label for="<?php echo 'reelDiameter_' . str_replace(' ', '_', strtolower($reelDiameter['reel_diameter'])) ?>"><input id='<?php echo 'reelDiameter_' . name_slug($reelDiameter['reel_diameter']) ?>' <?php echo (isset($facetData['reelDiameter']) && in_array($reelDiameter['reel_diameter'], $facetData['reelDiameter'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="reelDiameter[]" value="<?php echo $reelDiameter['reel_diameter'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;"><?php echo $reelDiameter['reel_diameter'] ?> (<?php echo $reelDiameter['total'] ?>)</div></label></a></li>
                            <?php endif ?>
                        <?php endforeach ?>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if (count($facets['discDiameters']) > 0): ?>
                <li>
                    <a class="dropdown-toggle" href="#">Disk Diameter</a>
                    <ul  data-role="dropdown" <?php if (isset($facetData['discDiameter'])): ?> style="display:block" <?php endif ?>>
                        <?php foreach ($facets['discDiameters'] as $discDiameter): ?>
                            <?php if ($discDiameter['disk_diameter'] != ''): ?>
                                <li><a href="javascript://"><label for="<?php echo 'diskDiameter_' . str_replace(' ', '_', strtolower($discDiameter['disk_diameter'])) ?>"><input id='<?php echo 'diskDiameter_' . name_slug($discDiameter['disk_diameter']) ?>' <?php echo (isset($facetData['discDiameter']) && in_array($discDiameter['disk_diameter'], $facetData['discDiameter'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="discDiameter[]" value="<?php echo $discDiameter['disk_diameter'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;"><?php echo $discDiameter['disk_diameter'] ?> (<?php echo $discDiameter['total'] ?>)</div></label></a></li>
                            <?php endif ?>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if (count($facets['recordingStandards']) > 0): ?>
                <li>
                    <a class="dropdown-toggle" href="#">Recording Standard</a>
                    <ul  data-role="dropdown" <?php if (isset($facetData['recordingStandard'])): ?> style="display:block" <?php endif ?>>
                        <?php foreach ($facets['recordingStandards'] as $recordingStandard): ?>
                            <?php if ($recordingStandard['recording_standard'] != ''): ?>
                                <li><a href="javascript://"><label for="<?php echo 'recordingStandard_' . str_replace(' ', '_', strtolower($recordingStandard['recording_standard'])) ?>"><input id='<?php echo 'recordingStandard_' . name_slug($recordingStandard['recording_standard']) ?>' <?php echo (isset($facetData['recordingStandard']) && in_array($recordingStandard['recording_standard'], $facetData['recordingStandard'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="recordingStandard[]" value="<?php echo $recordingStandard['recording_standard'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;"><?php echo $recordingStandard['recording_standard'] ?> (<?php echo $recordingStandard['total'] ?>)</div></label></a></li>
                            <?php endif ?>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endif; ?>
                
                <?php if (count($facets['accessLevel']) > 0): ?>
                <li>
                    <a class="dropdown-toggle" href="#">Access Level</a>
                    <ul  data-role="dropdown" <?php if (isset($facetData['accessLevel'])): ?> style="display:block" <?php endif ?>>
                        <div class="controls">
                            <div class="input-append">
                                <input style="  margin-left: 10px; height: 25px; width: 199px !important;" id="ac_filter" name="ac_filter" type="text" value="" >
                            </div>
                        </div>
                        <?php foreach ($facets['accessLevel'] as $key => $accessLevel): ?>
                            <?php if ($accessLevel['access_level'] != ''): ?>
                                <?php if ($key < 5) { ?>  
                                    <li><a href="javascript://"><label for="<?php echo 'accessLevel_' . str_replace(' ', '_', strtolower($accessLevel['access_level'])) ?>"><input id='<?php echo 'accessLevel_' . name_slug($accessLevel['access_level']) ?>' <?php echo (isset($facetData['accessLevel']) && in_array($accessLevel['access_level'], $facetData['accessLevel'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="accessLevel[]" value="<?php echo $accessLevel['access_level'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;" class="aLevels"><?php echo $accessLevel['access_level'] ?> (<?php echo $accessLevel['total'] ?>)</div></label></a></li>
                                <?php } else if ($key == 5) { ?>                        
                                    <a class="ac_ml" onclick="more_less('ac_ml')" style="text-decoration: underline;">
                                        More
                                        <b class="caret"></b>
                                    </a>
                                    <div id="ac_ml" style="display:none;">
                                        <li><a href="javascript://"><label for="<?php echo 'accessLevel_' . str_replace(' ', '_', strtolower($accessLevel['access_level'])) ?>"><input id='<?php echo 'accessLevel_' . name_slug($accessLevel['access_level']) ?>' <?php echo (isset($facetData['accessLevel']) && in_array($accessLevel['access_level'], $facetData['accessLevel'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="accessLevel[]" value="<?php echo $accessLevel['access_level'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;" class="aLevels"><?php echo $accessLevel['access_level'] ?> (<?php echo $accessLevel['total'] ?>)</div></label></a></li>
                                        <?php
                                    } else {
                                        ?>
                                        <li><a href="javascript://"><label for="<?php echo 'accessLevel_' . str_replace(' ', '_', strtolower($accessLevel['access_level'])) ?>"><input id='<?php echo 'accessLevel_' . name_slug($accessLevel['access_level']) ?>' <?php echo (isset($facetData['accessLevel']) && in_array($accessLevel['access_level'], $facetData['accessLevel'])) ? 'checked="checked"' : '' ?> type="checkbox" class="facet_checkbox" name="accessLevel[]" value="<?php echo $accessLevel['access_level'] ?>" style="float:left; width:20px"/><div style="margin-left: 25px;color: #696969;" class="aLevels"><?php echo $accessLevel['access_level'] ?> (<?php echo $accessLevel['total'] ?>)</div></label></a></li>
                                    <?php } ?>
                                <?php endif ?>
                            <?php endforeach; ?>
                            <?php
                            if (count($facets['accessLevel']) > 5) {
                                ?>
                                <a class="ac_ml" onclick="more_less('ac_ml')" style="display:none;text-decoration: underline;">Less</a>
                            </div>
                        <?php } ?>

                    </ul>
                </li>
               
            <?php endif; ?>
                     
            <li class="chekBoxFacet">
                <span id="is_review_check" style="cursor: default;">
                    <?php
                    $review_check = 0;
                    if (isset($facetData['is_review_check'])):

                        $review_check = $facetData['is_review_check'];
                    endif;
                    ?>
                    <input type="hidden" id="is_review_check_state" name="is_review_check" value="<?php echo $review_check ?>"  />
                </span>
                Manager Review
            </li>
            
            
            
        </ul>
    </nav>
    <?php
    $parentFacet = '';
    $totalChecked = 0;
    $keyword = "";
    if (isset($facetData['parent_facet']) && !empty($facetData['parent_facet'])):
        $parentFacet = $facetData['parent_facet'];
    endif;
    if (isset($facetData['total_checked']) && !empty($facetData['total_checked'])):
        $totalChecked = $facetData['total_checked'];
    endif;
    if (isset($facetData['facet_keyword_search']) && !empty($facetData['facet_keyword_search'])):
        $keyword = htmlentities($facetData['facet_keyword_search']);
    endif;
    ?>
    <input type="hidden" value="<?php echo $parentFacet; ?>" name="parent_facet" id="parent_facet" />
    <input type="hidden" value="<?php echo $totalChecked; ?>" name="total_checked" id="total_checked"/>
    <input type="hidden" value="<?php echo $keyword; ?>" name="facet_keyword_search" id="facet_keyword_search"/>
</form>

<script>
    function more_less(id) {
        $('.' + id).toggle('slow');
        $('#' + id).slideToggle('slow', function () {
        });
    }
    $("#formt_filter").bind("keyup", function () {
        var text = $(this).val().toLowerCase();
        if (text == '') {
            $('.format_ml').show();
            $('#format_ml').hide();
        } else {
            $('.format_ml').hide();
            $('#format_ml').show();
        }
        var items = $(".frmts");
        //first, hide all:
        items.parent().parent().hide();

        //show only those matching user input:
        items.filter(function () {
            return $(this).text().toLowerCase().indexOf(text) >= 0;
        }).parent().parent().show();
    });

    $("#collection_filter").bind("keyup", function () {
        var text = $(this).val().toLowerCase();
        if (text == '') {
            $('.collection_ml').show();
            $('#collection_ml').hide();
        } else {
            $('.collection_ml').hide();
            $('#collection_ml').show();
        }
        var items = $(".collections");
        //first, hide all:
        items.parent().parent().hide();

        //show only those matching user input:
        items.filter(function () {
            return $(this).text().toLowerCase().indexOf(text) >= 0;
        }).parent().parent().show();
    });
    
    $("#ac_filter").bind("keyup", function () {
        var text = $(this).val().toLowerCase();
        if (text == '') {
            $('.ac_ml').show();
            $('#ac_ml').hide();
        } else {
            $('.ac_ml').hide();
            $('#ac_ml').show();
        }
        var items = $(".aLevels");
        //first, hide all:
        items.parent().parent().hide();

        //show only those matching user input:
        items.filter(function () {
            return $(this).text().toLowerCase().indexOf(text) >= 0;
        }).parent().parent().show();
    });

    $("#parent_collection_filter").bind("keyup", function () {
        var text = $(this).val().toLowerCase();
        if (text == '') {
            $('.parent_collection_ml').show();
            $('#parent_collection_ml').hide();
        } else {
            $('.parent_collection_ml').hide();
            $('#parent_collection_ml').show();
        }
        var items = $(".parentCollection");
        //first, hide all:
        items.parent().parent().hide();

        //show only those matching user input:
        items.filter(function () {
            return $(this).text().toLowerCase().indexOf(text) >= 0;
        }).parent().parent().show();
    });

    $("#project_filter").bind("keyup", function () {
        var text = $(this).val().toLowerCase();
        if (text == '') {
            $('.project_ml').show();
            $('#project_ml').hide();
        } else {
            $('.project_ml').hide();
            $('#project_ml').show();
        }
        var items = $(".projects");
        //first, hide all:
        items.parent().parent().hide();

        //show only those matching user input:
        items.filter(function () {
            return $(this).text().toLowerCase().indexOf(text) >= 0;
        }).parent().parent().show();
    });

    $("#org_filter").bind("keyup", function () {
        var text = $(this).val().toLowerCase();
        if (text == '') {
            $('.org_ml').show();
            $('#org_ml').hide();
        } else {
            $('.org_ml').hide();
            $('#org_ml').show();
        }
        var items = $(".orgs");
        //first, hide all:
        items.parent().parent().hide();

        //show only those matching user input:
        items.filter(function () {
            return $(this).text().toLowerCase().indexOf(text) >= 0;
        }).parent().parent().show();
    });
</script>
