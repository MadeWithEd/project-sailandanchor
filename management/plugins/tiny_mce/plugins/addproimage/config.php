<?php
// ================================================
// tinymce PHP WYSIWYG editor control
// ================================================
// Configuration file
// ================================================
// Developed: j-cons.com, mail@j-cons.com
// Copyright: j-cons (c)2004 All rights reserved.
// ------------------------------------------------
//                                   www.j-cons.com
// ================================================
// v.1.0, 2004-10-04
// ================================================

// directory where tinymce files are located
$tinyMCE_dir = $_SERVER["DOCUMENT_ROOT"].'/management/tinymceditor';

// base url for images
$tinyMCE_base_url = '';

if (!ereg('/$', $HTTP_SERVER_VARS['DOCUMENT_ROOT']))
  $tinyMCE_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'].$tinyMCE_dir;
else
  $tinyMCE_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'].substr($tinyMCE_dir,1,strlen($tinyMCE_dir)-1);

// image library related config

// allowed extentions for uploaded image files
$tinyMCE_valid_imgs = array('gif', 'jpg', 'jpeg', 'png');

// allow upload in image library:
//$tinyMCE_upload_allowed = true; // not used anymore in iBrowser Ex
//$tinyMCE_img_delete_allowed = true; // not used anymore in iBrowser Ex


// allow delete in image library

$tinyMCE_imglibs = array(
		array (
                text => 'Main Library',
                value => '../../../../images/',
  				url => 'hhttp://www.cleanup.org.au/images/',
  				create_dir => true,
  				upload => true,
  				delete => true
        )
);


// image libraries
//  array(
//    'value'   => '',
//    'text'    => 'not configured',
//  ),
// );
// file to include in img_library.php (useful for setting $tinyMCE_imglibs dynamically
// $tinyMCE_imglib_include = '';
?>
