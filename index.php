<?php
  // REMOVE THE FOLLOWING 2 LINES IN PROIDUCTION!
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: GET, POST');  
?>
<html>
<head>
  <meta charset="UTF-8">
  <title>Euro-Pro Domain Status Utility</title>
  <script src="http://code.jquery.com/jquery-2.1.3.js" type="text/javascript"></script>
  <script src="script/script_lib.js" type="text/javascript"></script>
  <link rel="stylesheet" type="text/css" href="style/style_lib.css">
</head>
  <body>
    <h2>Euro-Pro Domain Status Utility<div id="faq">Click Here for Usage</div></h2>
    <div id="faq_details">
      How do I use this?
      <ul>
        <li>Export and download a CSV of all your domains from GoDaddy.com.</li>
        <li>Select that file below using the file chooser dialog. (No pre-formatting is necessary.)</li>
        <li>Click "<em>Submit</em>" and wait while the domains are queried and the results exported.</li>
        <li>When the CSV is complete, you will be prompted to download it automatically.</li>
      </ul>
    </div>
    <form id="file-submit-form" action="" method="POST" enctype="multipart/form-data">
      <p><input id="file-select" class="button" type="file" name="csv-file"/></p>
      <p><button id="submit-button" class="button" type="submit">Submit</button></p>
    </form>
    <p id="status-message"></p>
    <a name="bottom"></a>
  </body>
</html>
