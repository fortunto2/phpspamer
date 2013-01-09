phpspamer
=========

Send PHP mails to multiple users (html body, embedded images, mail lists, random subjects)

=========

<b>README</b>
- multy mails
- random subjects
- embeded images

This tool requires <a href="https://code.google.com/a/apache-extras.org/p/phpmailer/">Phpmailer</a> lib with different protocols.

Place file 'class.phpmailer.php' (and any other classes of this lib) in this project folder.


<b>CONFIGURATION</b>
edit file conf.php to setup all tools
- tplpath: folder with mail content
- imgindex: list of images in content folder (create it using format: <i>[cid]\t[filename]\n</i>, see USAGE 2,3)
- html: file with html mail body
- altbody: file with alternate plain text mail body
- prefix: a string with some path, using in html original template (see USAGE 4)
- smtphost and smtpport is your sender mail host
- xmailer: name of our mailer, use current or print any other like 'Outlook Express'
- fromaddr and fromname: part of "from" mail header
- subjects: file with list of subjects random selected for each sended mail
- emailvalidlist: empty file (may not be existed) which filled after mailchecker.php execution


<b>USAGE</b>
- 1. Make layout of your mail in HTML format and place it to tpl/template.html file.
- 2. Place all images to tpl folder too.
- 3. Make index for images like in file tpl/images.txt. Name if each index should be different. Name of pictures shoulb be in same register as file names.
- 4. If images in your layout has some path in src attribute of IMG tag, like 'images_folder/pic.png', edit line 3 of file 'image_cider.php' (ex: const prefix='images_folder/')
- 5. Run command: <i>php image_cider.php</i>. This tool replace all image pathes in file 'template.html' according to cid indexes
- 6. Fill mail_list.txt and subjects.txt
- 7. Run command: <i>php mailer.php</i>


<b>ADDITIONAL TOOLS</b>
- image_cider.php (see Usage 5.)
- mailchecker.php - get list of mails from file (Config - maillist), make request to mail domains, check for MX host and create a new file with check results (Config - emailvalidlist).
- valid2list.php - postchecking tool for cleanup mail checking result file (leave only valid emails). Run command: <i>php valid2list.php emailvalidlist.txt good_mails.txt</i>

