<?
/*******************

FlatGallery was written by Ben Craton (Tsunami.No.AI)
http://www.falseblue.com

This code is licenced under the GPL and may not be
redistributed in any altered form without the author's
express consent. The author is also not liable for any
damage this code may, or may not, cause. 

See the README file that came with your archive
for more information.

********************/

####################
#
#Configure:
#
# Title: The name of your gallery
$title="FlatGallery";

#Stylesheet: The style sheet you want to use
$stylesheet="style.css";

#Image Directory: Set where your images are located
#			This MUST be chmod 0777
$img_dir="images";

#Thumbs Directory: Set where your thumbnails will be placed
#			This MUST be chmod 0777
$thumb_dir="thumbs";

#Folder Icon: This image must be PNG and will be sized to $thumb_size
#			and saved as folder.png. If you change this from the deault
#			run rebuild_thumbs();
$folder_icon="folder_default_icon.png";

#Thumb Size: Set the maximum width/length of your thumbnails in pixels
$thumb_size=100;

#Cols: how many collumns to display per page
$cols=5;

#Rows: how many rows to display per page
$rows=50;

#Show filename or filesize: 0 for false, 1 for true
$show_name=0;
$show_size=1;

##########################
# DO NOT EDIT BELOW THIS LINE #
##########################
require("flatgallery.class.php");
$gal = new flatgallery();
$gal->set_title($title);
$gal->set_thumb_size($thumb_size);
$gal->set_cols($cols);
$gal->set_rows($rows);
$gal->set_show_filename($show_name);
$gal->set_show_filesize($show_size);

#Display: Displays the gallery
$gal->display_default();



?>

