tinyMCEPopup.requireLangPack();

var ImgManagerDialog = {
    cropedImageData: null,
    //INIT
    init : function() {
        var f = document.forms[0];
        
        $('#imageInserModal').modal({show:false});  //Image insert popup init
        this.openDirectory(null, null); // populate directories and files
        this.populateDirectoriesSelect("directoriesSelect","Choose directory to upload to:"); //Populate folder select infomation
        this.populateDirectoriesSelect("directoriesSelectParent","Choose parent directory:"); //Populate folder select infomation
        
        //Uploadify INIT
        $("#file_upload").uploadify({
            'uploader'  :  tinyMCEPopup.getWindowArg('plugin_url')+'/libs/uploadify.swf',
            'script'    : tinyMCEPopup.getWindowArg('plugin_url')+'/libs/ajax.php',
            'cancelImg' : 'img/cancel.png',
            'folder'    : '',
            'auto'      : false,
            'multi'       : true,
            'scriptData': {"uploadify":1,"hash":hash}
        });
    },
    close : function() {
        tinyMCEPopup.popup_css = false;
        tinyMCEPopup.close();
    },
    insert : function() {
        // Insert the contents from the input into the document
        
        var dialog = $("#imageInserModal");
        var alt = dialog.find("#imgAlt").val();
        var width = dialog.find("#imgWidth").val();
        var height = dialog.find("#imgHeight").val();
        var align = dialog.find("#imgAlign").val();
        var src = dialog.find("#imgWebUrl").val();
        
        var html = '<img src="'+src+'" ';
        var img = $("<img>",{"src":src});
        if(alt){img.attr("alt",alt);html = html+' alt="'+alt+'"';}
        if(width){img.attr("width",width);html = html+' width="'+width+'"';}
        if(height){img.attr("height",height);html = html+' height="'+height+'"';}
        if(align){img.attr("align",align);html = html+' align="'+align+'"';}
        html = html + ' />';
        
        var ed = tinyMCEPopup.editor, el;
        
        tinyMCEPopup.restoreSelection();
        
        if (tinymce.isIE){
            ed.selection.setContent(html);
        }else{
            el = ed.selection.getNode();
            $(el).append(img);
        }
        
        ed.undoManager.add();
        
        tinyMCEPopup.popup_css = false;
        tinyMCEPopup.close();
    },
    showTab: function(tabId){
        jQuery(".contentContainers").hide();
        jQuery("#"+tabId).show();
        return false;
    },
    openDirectory: function (dirName, parent){    
            if(parent && parent != "/" && parent != "../"){
                if(dirName)
                    dirName = parent+"/"+dirName;
                else
                    dirName = parent;
            }
            
            if(dirName == parent || parent == "../"){
                parent = Helppers.getParentDir(dirName);
            }
            
            $.ajax({
                url: tinyMCEPopup.getWindowArg('plugin_url')+'/libs/ajax.php?getFilesAndDirectories=1&dir='+dirName,
                type: 'GET',
                dataType:  'text json',
                success:function(data){
                    Helppers.populateFoldersAndImages(data,dirName,parent);
                },
                error:function(){
                    alert("Error eccured. Please refresh site and try again");
                }
            });
        
        return false;
    },
    populateDirsAfterUpdate: function(){
        this.populateDirectoriesSelect("directoriesSelect","Choose directory to upload to:");
        this.populateDirectoriesSelect("directoriesSelectParent","Choose parent directory:");
    },
    populateDirectoriesSelect: function(container_id, label){
        $.ajax({
            url: tinyMCEPopup.getWindowArg('plugin_url')+'/libs/ajax.php?getDirectoryList=1',
            type: 'GET',
            dataType:  'text json',
            success:function(data){
                $("#"+container_id).html("");
                var labelSelect = $("<label>", {"for": "directory"}).html("<h4>"+label+"</h4>");
                var select = $("<select>", {"id": "directory"});
                
                select.change(function(){
                    $("#file_upload").uploadifySettings("scriptData",{'dir':$("#"+container_id).find("#directory").val()});
                });
                
                select.append($("<option>",{"value":""}).html("Main directory"));
                $.each(data,function(key,dir){
                    select.append($("<option>",{"value":key,"label":dir}).html(dir));
                });
                $("#"+container_id).append(labelSelect);
                $("#"+container_id).append(select);
            },
            error:function(){
                alert("Error eccured. Please refresh site and try again");
            }
        });
    },
    addNewFolder: function(dir, folder){
        
        if(!folder){
            alert('No directory name provided !');
            return false;
        }
        
        $.ajax({
            url: tinyMCEPopup.getWindowArg('plugin_url')+'/libs/ajax.php?addDirectory=1&dir='+dir+'&folder='+folder,
            type: 'GET',
            dataType:  'text json',
            success:function(data){
                $("#dirAddSuccess").show("slide").delay(2000).hide("slide");
                ImgManagerDialog.populateDirsAfterUpdate();
                $("#newFolderName").val("");
            },
            error:function(){
                alert("Error eccured. Please refresh site and try again");
            }
        });
    },
    getImageInformations: function(imgUrl){
         
        var return_data = null;
        $.ajax({
            async: false,
            url: tinyMCEPopup.getWindowArg('plugin_url')+'/libs/ajax.php?getImageInformation=1&url='+imgUrl,
            type: 'GET',
            dataType:  'text json',
            success:function(data){
                return_data = data;
            },
            error:function(){
                alert("Error eccured. Please refresh site and try again!");
            }
        });
        return return_data;
    },
    insertImageDialog: function(imgUrl){
        var modal = $('#imageInserModal');
        modal.find("#imgThumb").attr("src",imgUrl);
        
        var data = this.getImageInformations(imgUrl);
        
        modal.find("#imgAlt").val(data.name);
        modal.find("#imgWidth").val(data.width);
        modal.find("#imgHeight").val(data.height);
        modal.find("#imgOrigWidth").val(data.width);
        modal.find("#imgOrigHeight").val(data.height);
        modal.find("#imgWebUrl").val(data.web_base_url);
        modal.modal('show');
        
    },
    cropImage: function(imgUrl){
        var modal = $('#imageCropModal');
        modal.find("#cropImgThubContainer").html("").append($("<img>",{"src":"","id":"imgCrop"}));
        var data = this.getImageInformations(imgUrl);
        this.cropedImageData = data;
            
        var this_boxHeight = 400;
        var this_boxWidth = 460;

        if(data.height > data.width){
            modal.find("#cropImgThubContainer").removeClass("span6").addClass("span4").attr("style","margin-left:130px;");
            modal.attr("style","min-height:580px;");
            this_boxHeight = 430;
            this_boxWidth = 450;
            
        }else{
            modal.find("#cropImgThubContainer").removeClass("span4").addClass("span6").attr("style","");
            modal.attr("style","");
        }
        modal.find("#imgCrop").attr("src",imgUrl).Jcrop({
                boxWidth: this_boxWidth, 
                boxHeight: this_boxHeight,
                onSelect: Helppers.jCropUpdateCoords
        });
        modal.modal('show');
        
    },
    cropAndSave: function(overwrite){
        
        if(Helppers.jCropCheckCoords()){
            $.ajax({
                async: false,
                url: tinyMCEPopup.getWindowArg('plugin_url')+'/libs/ajax.php?cropImg=1',
                type: 'POST',
                data:{
                    imgData:    ImgManagerDialog.cropedImageData,
                    overwrite:  overwrite,
                    cW:         $("#w").val(),
                    cH:         $("#h").val(),
                    cX:         $("#x").val(),
                    cY:         $("#y").val()
                },
                dataType:  'text json',
                success:function(data){
                    ImgManagerDialog.openDirectory(null, null);
                    $('#imageCropModal').modal('hide');
                },
                error:function(){
                    alert("Error eccured. Please refresh site and try again!");
                }
            });
        }
    },
    initImageUpload : function(){
        if (tinymce.isIE){
            $('#dropbox').hide();
            $('#uploadify').show();
        }else{
            $('#dropbox').html('<span class=\'message\'><h4>Drop images here to upload.</h4></span>');
        }
    }
};

tinyMCEPopup.onInit.add(ImgManagerDialog.init, ImgManagerDialog);



var Helppers = {
    init: function(){
        
    },
    populateFoldersAndImages: function(data, dirName, parentDirName){
        if(data){
            //Empty all containers
            $("#directoriesContainer").html("");
            $("#filesContainer").html("");
            //$("#hFilesContainer").html("");
            
            //Populate directories
            
                var dir_ul = $("<ul>", {"class": "thumbnails"});
                if(parentDirName || dirName){
                    dir_ul.append(Helppers.getFolderHtml(dirName,parentDirName,true));
                }
                if(data.directories){
                    $.each(data.directories,function(key,dir){
                        dir_ul.append(Helppers.getFolderHtml(dir,dirName));
                    });
                }
                $("#directoriesContainer").append(dir_ul);
            
            
            //Populate images
            var files_ul = $("<ul>", {"class": "thumbnails"});
            if(data.files){
                $.each(data.files,function(key,fileName){
                    var fileUrl = data.base_url+"/"+fileName;
                    if(dirName && dirName != "null"){
                        fileUrl = data.base_url+"/"+dirName+"/"+fileName;
                    }
                    files_ul.append(Helppers.getImageHtml(fileUrl));
                });
                
            }
            $("#filesContainer").append(files_ul);
        }
    },
    getImageHtml: function(fileName){
        var li = $("<li>", {"class": "span2","style":"min-width:130px;min-height:120px;"});
        var divThumb = $("<div>", {"class": "thumbnail" ,"style":"width:130px;height:120px;"});
        var img = $("<img>", {"src": fileName,"alt": "","height":"80"});
        var divActions = $("<div>", {"class": "btn-group caption"});
        var actionA = $("<a>",{"class":"btn dropdown-toggle","data-toggle":"dropdown","href":"#"}).html("Action").append($("<span>",{"class":"caret"}));
        var ulAction = $("<ul>",{"class":"dropdown-menu"});
        ulAction.append($("<li>").append($("<a>").html('<i class="icon-play-circle"></i>  Insert').click(function(){
            ImgManagerDialog.insertImageDialog(fileName);
        })));
        ulAction.append($("<li>").append($("<a>").html('<i class="icon-edit"></i>  Crop').click(function(){
            ImgManagerDialog.cropImage(fileName);
        })));
        ulAction.append($("<li>").append($("<a>").html('<i class="icon-trash"></i> Delete').click(function(){
            if(confirm("Are you sure you want to delete file ? ")){
                $.ajax({
                    url: tinyMCEPopup.getWindowArg('plugin_url')+'/libs/ajax.php?deleteFile=1&file='+fileName,
                    type: 'GET',
                    dataType:  'text json',
                    success:function(){
                        li.remove();
                    },
                    error:function(){
                        alert("Error eccured. Please refresh site and try again");
                    }
                });
            }
        })));
        
        divActions.append(actionA);
        divActions.append(ulAction);
        divThumb.append(img);
        divThumb.append(divActions);
        li.append(divThumb);
        return li;
    },
    getFolderHtml: function(dirName,parentDirName,back){
        var li = $("<li>", {"class": "span2","style":"min-width:130px;min-height:150px;"});
        var divThumb = $("<div>", {"class": "thumbnail","style":"width:130px;height:150px;"});
        
        var img = $("<img>", {"src": "img/folder.png","alt": "","class": "cursor","height":"80"}).click(function(){ImgManagerDialog.openDirectory(dirName,parentDirName)});
        if(back){
            img = $("<img>", {"src": "img/back.png","alt": "","class": "cursor","height":"80"}).click(function(){ImgManagerDialog.openDirectory(parentDirName,"../")});
        }
        
        var divActions = $("<div>", {"class": "caption"});
                
        if(back){
            divActions.append($("<p>").append($("<a>", {"class": "btn"}).html("Back").click(function(){ImgManagerDialog.openDirectory(parentDirName,"../")}) ));
        }else{
            divActions.append( $("<p>").html(dirName) );
            divActions.append( $("<p>").append($("<a>", {"class": "btn btn-info"}).html('<i class="icon-trash icon-white"></i> Delete').click(function(){
                if(confirm("Are you sure you want to delete folder and it's content ? ")){
                    var dir = dirName;
                    if(parentDirName){
                        dir = parentDirName+"/"+dirName;
                    }
                    
                    $.ajax({
                        url: tinyMCEPopup.getWindowArg('plugin_url')+'/libs/ajax.php?deleteFolder=1&folder='+dir,
                        type: 'GET',
                        dataType:  'text json',
                        success:function(){
                            li.remove();
                            ImgManagerDialog.populateDirsAfterUpdate();
                        },
                        error:function(){
                            alert("Error eccured. Please refresh site and try again");
                        }
                    });
                }
            })));
        }
                
        
        divThumb.append(img);
        divThumb.append(divActions);
        li.append(divThumb);
        return li;
    },
    getParentDir: function(parentDirName){
        if(parentDirName){
            if(parentDirName.split("/").length == 1 || parentDirName == "../"){
                parentDirName = null;
            }else{
                var split = parentDirName.split("/");
                split.pop();
                parentDirName = split.join("/");
            }
        }
        return parentDirName;
    },
    toogleConstrains: function(item){
        if($(item).attr("constrains") == 1){
            $(item).attr("id","constrains_0");
            $(item).attr("constrains","0");
        }else{
            $(item).attr("id","constrains_1");
            $(item).attr("constrains","1");
        }
    },
    checkConstrains: function(changed)
    {
        var dialog = $("#imageInserModal");
        var constrains = dialog.find(".constrains");
        if(constrains.attr("constrains") == 1)
        {
            
            var orginal_width = dialog.find("#imgOrigWidth").val();
            var orginal_height = dialog.find("#imgOrigHeight").val();

            var widthObj = dialog.find("#imgWidth");
            var heightObj = dialog.find("#imgHeight");

            var width = parseInt(widthObj.val());
            var height = parseInt(heightObj.val());

            if(orginal_width > 0 && orginal_height > 0)
            {
                if(changed == 'width' && width > 0) {
                    heightObj.val( parseInt((width/orginal_width)*orginal_height) );
                }

                if(changed == 'height' && height > 0) {
                    widthObj.val( parseInt((height/orginal_height)*orginal_width) );
                }
            }
        }
    },
    jCropUpdateCoords: function(c){
        jQuery('#x').val(c.x);
	jQuery('#y').val(c.y);
	jQuery('#w').val(c.w);
	jQuery('#h').val(c.h);
    },
    jCropCheckCoords: function(){
        if (parseInt(jQuery('#w').val())>0) return true;
	alert('Please select a crop region.');
	return false;
    }
    
};


