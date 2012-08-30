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

Creating your own application
-----------------------------
If you want to create your own version of this application you can start <a href="https://developers.facebook.com/apps" target="_blank">here</a>. Create a new app and go to its Basic Settings. Select __Page Tab__ when asked how your app integrates with Facebook. Once you have hosted the Kaltura Media Page application files on your server you can point the __Page Tab URL__ to _index.php_ and the __Page Tab Edit URL__ to _admin.php_. As you can see there is also a __Secure Page Tab URL__ required which must point to the __https__ version of _index.php_. With the current CSS settings the Page Tab will look best if you set __Page Tab Width__ to _Wide (810px)_. Save your changes and copy the application's App ID/API Key into _config.php_. You should now have a fully functional Facebook Page Tab application of your own!

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

* lib/chosen - Contains the <a href="http://harvesthq.github.com/chosen/" target="_blank">Chosen</a> javascript plugin
* lib/loadmask - Contains the <a href="http://code.google.com/p/jquery-loadmask/" target="_blank">loadmask</a> jQuery plugin
* lib/php5 - Contains the <a href="http://www.kaltura.com/api_v3/testme/client-libs.php" target="_blank">Kaltura PHP5 Client Library</a>
* lib/select2 - Contains the <a href="http://ivaynberg.github.com/select2/" target="_blank">Select2</a> library