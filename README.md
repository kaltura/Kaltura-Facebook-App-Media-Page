Kaltura Media Page Tab for Facebook Pages
=====================================
Kaltura Media Page allows you to create a gallery of videos for your Facebook Page. When users visit your Facebook page they can click on the MediaPage tab and view a category or playlist of videos that you preselect using the administrator console.

How to get started
------------------
Simply <a href="https://www.facebook.com/dialog/pagetab?app_id=340820556008773&next=http://204.236.255.97/MediaPage/" target="_blank">click here</a> and add the Page Tab to your desired Facebook Page. Once added, the application will redirect you to the Added Apps for your Facebook Page. Find MediaPage and click __Go to App__ to access the administrator console where you may log in using your Kaltura credentials. Just select a player from your Kaltura account and then either a category or a playlist. Then select 3 featured videos from your category/playlist that you would like prominently featured below the main player. Once you are done making your selections, submit the settings and you will be taken to your gallery.

Accessing the Admin Console
---------------------------
Facebook has made accessing the admin screen (Page Tab Edit URL) for Page Tabs particularly cryptic. As of writing this README, here's what you need to do to find it:
* Go to your Facebook Page 
* From the Admin Panel, click __Edit Page__ and you should get a dropdown menu
* Click on __Update Info__
* Click __Apps__ in the sidebar that appears
* Find the MediaPage application and click __Go to App__

Files
-----

* admin.php - The front page for the admin console which is used to set up the Page Tab's gallery
* appStyle.css - Style for the gallery and admin console
* config.php - Stores the Facebook Tab's App ID (If you are making your own Facebook App you must update this file)
* getFeatured.php - Grabs the featured videos that were selected in the admin console
* getSession.php - Used to generate a Kaltura Session when logging in
* getThumbnail.php - Loads the thumbnail for a video chosen in the admin console
* index.php - The front page for the Page Tab that displays the Kaltura gallery
* partnerSelect.php - Displays the selector for choosing a Partner on the user's Kaltura account
* refreshSelector.php - Refreshses the selectors in the admin console for choosing videos from your category or playlist
* reloadEntries.php - Loads the thumbnail gallery for the category or playlist chosen in the admin console
* savePage.php - Stores the selected options for the user's Facebook Page
* showSetup.php - Loads the page that allows the Page Tab to be configured

Folders
-------

* lib/chosen - Contains the Chosen javascript plugin (http://harvesthq.github.com/chosen/)
* lib/loadmask - Contains the loadmask jQuery plugin (http://code.google.com/p/jquery-loadmask/)
* lib/php5 - Contains the Kaltura PHP5 client library (http://www.kaltura.com/api_v3/testme/client-libs.php)
* lib/select2 - Contains the Select2 library (http://ivaynberg.github.com/select2/)