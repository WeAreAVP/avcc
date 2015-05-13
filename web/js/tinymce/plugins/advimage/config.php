<?php

// As default, the uploaded image will be resized to this size
define('DEFAULT_IMG_WIDTH', 580);

// There will in addition to the resized image be created a thumbnail. This is the thumbnail size
define('DEFAULT_THUMB_WIDTH', 100);

// The quality of the resized image - 0 = lowest -> 100 = highest
define('IMAGE_QUALITY', 100);

// When managing the images - this in the path to the resized image
define('IMG_PATH', '/var/www/html/uploads'); 

// When uploading the image - the original will be uploaded, then deleted from this dir
define('IMG_PATH_TEMP', '/var/www/html/uploads');

// This is the actual path to the image in the article
define('IMG_PATH_LIVE', 'var/www/html/uploads'); 

// Every directory will have a subfolder containing thumbnails. This is the subfolders name. 
define('THUMB_DIR', '/thumbs/');

// Information in the upload section
define('UPLOAD_INFO', 'St�ttede bildeformater er .png, .jpg, .gif. For best presentasjon b�r bilde ha en bredde p� minimum 500px 
	f�r det lastes opp og bildets st�rrelse b�r v�re under 2 MB.');
?>