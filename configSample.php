<?php
// database hostname, you don't usually need to change this
define('db_host','localhost');
// database username
// database username
define('db_user','glitchwi_GlitchWizard');
// database password
define('db_pass','H20water!');
// database name
define('db_name','glitchwi_joeyPhotoAlbum_db');
// database charset, change this only if utf8 is not supported by your language
define('db_charset','utf8');
/* Media */
// Media popup dialog enabled?
define('media_popup', true);
// Media grid width in pixels
define('media_grid_default_width', 354);
// Media grid height in pixels
define('media_grid_default_height', 240);
/* Upload */
// Image quality - the image is compressed with a value lower than 100 and the file size is reduced
define('image_quality', 100);
// Correct image orientation for mobile upload
define('correct_image_orientation', false);
// Image max width; -1 = no max width
define('image_max_width', -1);
// Image max height; -1 = no max height
define('image_max_height', -1);
// Maximum filesize for uploaded files, measured in bytes
// Image max filesize; 5MB default
define('image_max_size', 5000000);
// Audio max filesize; 20MB default
define('audio_max_size', 20000000);
// Video max filesize; 100MB default
define('video_max_size', 100000000);
// Upload approval required
define('approval_required', true);
// Authentication required for uploading files
define('authentication_required', true);
// PHP global settings; you can either adjust the values below or edit them in the php.ini file
// Increase the max upload file size
ini_set('post_max_size', '200M');
ini_set('upload_max_filesize', '200M');
?>