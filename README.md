flatgallery
===========

FlatGallery is a simple image gallery producer that requires no database backend. 

FlatGallery was written by Ben Craton (Tsunami.No.Ai)
http://www.falseblue.com/flatgallery/

This code is licenced under the GPL and may not be
redistributed in any altered form without the author's
express consent. The author is also not liable for any
damage this code may, or may not, cause. 

-----------
Contents
-----------

I.   About
II.  Requirements
III. Installation
IV.  Detailed HowTo
V.   ToDo
VI.  ChangeLog

-----------
I. About
-----------

FlatGallery was written by myself after being distraught 
to find that there were no good PHP gallery scripts that 
were simple enough to use without a database backend. Sure,
you have several scripts that generate thumbnails "on the 
fly", but these normaly generate thumbnails for every image
every time a page is refreshed. This taxing burden on the
server is wasteful to me and I desided to do something 
about it.

FlatGallery will simply sit in a folder, read the images from 
whatever directory you set it to, and generate thumbnails into 
another folder. This caching is done only when new images are 
added, or when the rebuild_thumbs function is used. If images 
are removed from the directory, thumbnails are disposed of 
automaticaly. The result is a happy server and a fast gallery 
for you.


-----------
II. Requirements
-----------

FlatGallery requires the following:

A server
PHP
GDlib
A writable folder
Pictures

-----------
III. Installation
-----------

FlatGallery runs right out of the box. Simply drop the files 
in this archive into a folder and youre ready to go.

By default, FlatGallery will try and read from subdirectories 
images/ and thumbs/. These must already exist, or you can 
change them by editing index.php. Both folders must be 
chmoded to 0777. They CANNOT be the same folder.

Add files to the image directory and navigate to the site 
with your browser. Whenever new images are added to the 
directory, FlatGallery will create and cache thumbnails 
into your thumb directory. This processes may take a few 
moments when first run depending on the number and size 
of images. 

Sub-Galleries can be created in images/ by making a new
folder with the name of the sub-gallery. Folder names can 
only contain alphanumeric, spaces, and underscore characters.
Underscores will be interpruted at spaces in the gallery title.
As of 2.0 sub-galleries can only go down 1 level.

If FlatGallery says its out of memory, you are probably 
trying to add images over 1MB to the gallery on a server
 with insuffecient memory. Try using a smaller image or 
get more memory.

If FlatGallery says a file is unreadable or a bad file, 
simply remove that file.

When you remove an image from the image directory, 
FlatGallery will automaticaly remove the thumbnail 
associated with it.

To rebuild all your thumbnails, uncomment the line 
"$gal->rebuild_thumbs()" in index.php and refresh your 
gallery. After a moment, FlatGallery will have flushed 
and rebuilt your thumnail cache. Re-comment this line 
immediately as any more refreshes will also flush and 
rebuild the cache, putting strain on your server.


-----------
IV. Detailed HowTo
from http://flatgallery.sourceforge.net
-----------

Quick Install
-------------

FlatGallery can be installed right out of the box with no need to configure anything (this is boring though).

Download the lateset version of FlatGallery

Untar the archive into your web directory:
tar -zxvf flatgallery_X.X.tar.gz

Chmod the images/ directory to 0755:
chmod -R 0755 images/

Chmod the thumbs/ directory to 0777:
chmod -R 0777 thumbs/

Put any number of Jpeg, Gif, or Png images in the images folder and then navigate to your your directory:
http://my.webhost.com/flatgallery_X.X/



Configuring FlatGallery
-----------------------

FlatGallery has a number of configurations included with the default install. To use them, open index.php in your favorite text editor and edit the following values.

$title: Set this to the name of your gallery.

$stylesheet: Set this to the filename of your style sheet.

$img_dir: Set this to the directory which will hold your images (must be chmod 0777).

$thumb_dir: Set this to the directory which will hold your thumbnail cache (must be chmod 0777).

$folder_icon: Set this to a PNG image that will be the icon for folders (sub-galleries). You may need to run rebuild_thumbs() to make a nicely sized icon named "folder.png". 

$cols: Set this to how many columns you want your gallery to have.

$rows: Set this to how many rows you want your gallery to have.

$show_name: Set this to display the filename under the thumbnail. (1 on, 0 off)

$show_size: Set this to display the file size under the thumbnail. (1 on, 0 off)

Changing the look and feel of FlatGallery is as simple as editing or replacing the stylesheet.


Integrating FlatGallery into an Existing Site
---------------------------------------------

While FlatGallery will run just fine out of the box, you may want to integrate FlatGallery into a page that you already made. This site here is an example of that idea. The set up is for more advanced PHP users, but its still fairly straight forward.

Make sure that you link to the style sheet in the header as XHTML compliant.

Have the following line at the top of your PHP code
require("flatgallery.class.php"); 

You will need to use $gal->set_link_requisit(string); when integrating. Set string to whatever needs to be in the GET variables with $offset. (example: the demo on this page uses "page=demo" for a link requisit for next and prev page links)

Read and use the class function definitions below.



FlatGallery Class Functions
---------------------------

Note: Only user functions are documented at this time. Internal function documentation will be forthcoming.

$gal = new flatgallery(void); 
Class constructor

$gal->set_title(string); 
Sets the title variable.

$gal->set_style(string); 
Sets the stylesheet url.

$gal->set_dir(string); 
Sets the images directory.

$gal->set_thumb_dir(string); 
Sets the thumbnail cache directory.

$gal->set_thumb_size(int); 
Sets the max length/width of the thumbnails in pixels.

$gal->set_folder_icon(int); 
Sets the PNG image FlatGallery will refer to when building the resized icon "folder.png". 

$gal->set_cols(int); 
Sets the number of columns displayed per page.

$gal->set_rows(int); 
Sets the number of rows displayed per page.

$gal->set_offset(int); 
Sets the image number offset. (to work properly this MUST be set to $_GET['offset'] )

$gal->set_offset(int); 
Sets the gallery being viewed. Blank value will default to the parent gallery. (to work properly this MUST be set to $_GET['gallery'] )

$gal->set_show_filename(int); 
Sets the display of the filename below the thumbnail to on or off (1 or 0).

$gal->set_show_filensize(int); 
Sets the display of the filesize below the thumbnail to on or off (1 or 0).

$get->set_link_requisit(string); 
Sets the link requisits for the Next and Prev page links. This is to be used when FlatGallery is integrated into an existing page that may use GET variables to control content. (example: the demo on this page uses "page=demo" as a requisit.)

$gal->do_parse(void); 
Calls FlatGallery to search through the image directory and make sure all images have coresponding thumbnails in the thumbs directory. If they dont, it creates them. It then parses the thumbs directory and removes thumbnails which dont have a coresponding image in the images directory (ie. it was deleted or renamed).

$gal->head(void); 
Prints out an XHTML valid header.

$gal->link_stylesheet(void); 
Prints out the <link> line for the stylesheet. (This functions use outside of the <head> block is not XHTML complient).

$gal->display(void); 
Displays the gallery page.

$gal->foot(void); 
Prints out an XHTML valid footer.

$gal->rebuild_thumbs(void); 
Will destroy all thumbnails and rebuild them based on the current file list of the image directory. This function should never be allowed to run each time the page is loaded (that would defeat the purpose of FlatGallery).


