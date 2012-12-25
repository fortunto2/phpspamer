phpspamer
=========

Send PHP mails to multiple users (html body, embedded images, mail lists, random subjects)

=========

README:

- multy mails
- random subjects
- embeded images


This tool required "phpmailer" lib with different protocols.
https://code.google.com/a/apache-extras.org/p/phpmailer/ 

Place file 'class.phpmailer.php' (and any other classes of this lib) in this project folder.


USAGE
1. Make layout of your mail in HTML format and place it to tpl/template.html file.
2. Place all images to tpl folder too.
3. Make index for images like in file tpl/images.txt. Name if each index should be different. Name of pictures shoulb be in same register as file names.
4. If images in your layout has some path in src attribute of <IMG> tag, like 'images_folder/pic.png', edit line 3 of file 'image_cider.php' (ex: 	const prefix='images_folder/')
5. Run command: php image_cider.php
This tool replace all image pathes in file 'template.html' according to cid indexes
6. Fill mail_list.txt and subjects.txt
7. Run command: php mailer.php
