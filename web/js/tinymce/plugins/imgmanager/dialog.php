<?php require_once 'libs/config.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
    <head>
        <meta charset="utf-8">
        <title>Image Manager</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Image Manager">
        <meta name="author" content="Darius Matulionis http://matulionis.lt">

        <link href="css/bootstrap.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script type="text/javascript"> var hash = "<?= ENCRIPTED_SESSION_ID ?>";</script>
        <script type="text/javascript" src="../../tiny_mce_popup.js"></script>
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery.filedrop.js"></script>
        <script type="text/javascript" src="js/script.js"></script>
        <script type="text/javascript" src="js/swfobject.js"></script>
        <script type="text/javascript" src="js/jquery.uploadify.v2.1.4.min.js"></script>
        <script type="text/javascript" src="js/jquery.Jcrop.min.js"></script>
        <script type="text/javascript" src="js/dialog.js"></script>
    </head>
    <body>
        <br/>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span2 well">
                    <ul class="nav nav-list">
                        <li class="nav-header">Actions</li>
                        <li><a href="#" onclick="ImgManagerDialog.showTab('tab1'); ImgManagerDialog.openDirectory(null, null)"><i class="icon-book"></i> Library</a></li>
                        <li><a href="#" onclick="ImgManagerDialog.showTab('tab2'); ImgManagerDialog.initImageUpload();"><i class="icon-upload"></i> Upload Images</a></li>
                        <li><a href="#" onclick="ImgManagerDialog.showTab('tab3');"><i class="icon-folder-open"></i> Add Folder</a></li>
                        <li><a href="#" onclick="ImgManagerDialog.close();"><i class="icon-stop"></i> Close</a></li>
                    </ul>
                </div>
                <div class="span8 well contentContainers" id="tab1" style="display: block;">
                    <h3>Library</h3>
                    <div style="max-height: 250px; overflow: scroll;">
                        <div id="directoriesContainer"><!-- Directories content goes here --></div>
                    </div>
                    <h3>Images</h3>
                    <div style="max-height: 400px; overflow: scroll;">
                        <div id="filesContainer"><!-- Files content goes here --></div>
                        <div class="clearfix" style="height: 70px;"></div>
                    </div>
                </div>
                <div class="span8 well contentContainers" id="tab2" style="display: none;">
                    <h3>Upload Images</h3>
                    <br/>
                    <div id="directoriesSelect"></div>
                    <div id="dropbox"></div>
                    <div id="uploadify" style="display: none;">
                        <input id="file_upload" type="file" name="file_upload" />
                        <a href="javascript:$('#file_upload').uploadifyUpload();" class="btn btn-success pull-right">Upload Files</a>
                    </div>
                </div>
                <div class="span8 well contentContainers" id="tab3" style="display: none;">
                    <h3>Add Folder</h3>
                    <br/>
                    <div class="alert alert-success" id="dirAddSuccess" style="display: none;">Directory added successfully</div>
                    <div id="directoriesSelectParent"></div>
                    <label for="newFolderName">Folder Name</label>
                    <input type="text" name="newFolderName" id="newFolderName" value="" />
                    <div class="clearfix"></div>
                    <button class="btn btn-success" href="#" onclick="ImgManagerDialog.addNewFolder($('#directoriesSelectParent').find('#directory').val(),$('#newFolderName').val())">Add Folder</button>
                </div>
            </div>
        </div>
    </body>
</html>

<div class="modal fade" id="imageInserModal" style="display: none; ">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3>Insert Image</h3>
    </div>
    <div class="modal-body">
        <div class="thumbnail span4 pull-left" style="min-height: 150px;">
            <input type="hidden" value="" id="imgOrigWidth" />
            <input type="hidden" value="" id="imgOrigHeight" />
            <input type="hidden" value="" id="imgWebUrl" />
            <div class="pull-left">
                <label for="imgWidth" class="control-label">Width </label>
                <input type="text" value="" id="imgWidth" class="input-small" tabindex="1" onblur="Helppers.checkConstrains('width');"/>
            </div>
            <div class="span1">
                <input type="text" id="constrains_1" class="constrains" constrains="1" onclick="Helppers.toogleConstrains(this);"/>
            </div>
            <div class="pull-right">
                <label for="imgHeight"  class="control-label">Height </label>
                <input type="text" value="" id="imgHeight" class="input-small" tabindex="2" onblur="Helppers.checkConstrains('height');"/>
            </div>
            <div class="pull-left">
                <label for="imgAlt" class="control-label">Alt </label>
                <input type="text" value="" id="imgAlt" tabindex="3"/>
            </div>

            <div class="pull-left">
                <label for="imgAlign" class="control-label">Positioning of this image </label>
                <select title="Positioning of this image" id="imgAlign">
                    <option value=""></option>
                    <option value="left">Left</option>
                    <option value="right">Right</option>
                    <option value="bottom">Bottom</option>
                    <option value="middle">Middle</option>
                    <option value="top">Top</option>
                </select>
            </div>
        </div>
        <div class="thumbnail span2 pull-right"><img id="imgThumb" src="" height="100px"  /></div>
        <div class="clearfix"></div>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-success" onclick="ImgManagerDialog.insert();">Insert Image</a>
        <a href="#" class="btn" onclick="$('#imageInserModal').modal('hide')">Close</a>
    </div>
</div>

<div class="modal fade" id="imageCropModal" style="display: none;">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3>Crop Image</h3>
    </div>
    <div class="modal-body">
        <input type="hidden" id="x" name="x" />
        <input type="hidden" id="y" name="y" />
        <input type="hidden" id="w" name="w" />
        <input type="hidden" id="h" name="h" />
        <div class="thumbnail span6" id="cropImgThubContainer">
            <img id="imgCrop" src="" />
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-success" onclick="ImgManagerDialog.cropAndSave()">Crop and save copy</a>
        <a href="#" class="btn btn-success" onclick="ImgManagerDialog.cropAndSave(true)">Crop and overwrite</a>
        <a href="#" class="btn" onclick="$('#imageCropModal').modal('hide')">Close</a>
    </div>
</div>
