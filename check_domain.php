<?php
  $domain = $_POST["domain"];
  # echo "Checking domain " . $i . " of " . count($domain_list) + 1;
  /* Prefix the rest of the domain to make this fetchable by cURL. */
  $full_domain = "http://www." . strtolower($domain);

  /* Instatiate a new cURL handle */
  $ch = curl_init();

  /* Set options. We want the headers, the body, the whole enchilada. Enchilada is a very technical term. */
  curl_setopt($ch, CURLOPT_URL, $full_domain);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");

  /* Collect our response. Pass Go. Etc. */
  $response = curl_exec($ch);

  /* I cheated and copied this from the web. But basically, it measures the response is returned as a string.
   We grab the header size, then use substr to cut it out of the string and save it separately.
   Then start where the header ends and cut out the body of the response also using substr.
   We'll need the body to see if the domain is parked or not. */
  $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
  $header = substr($response, 0, $header_size);
  $body = substr($response, $header_size);

  /* Save the status code. Useful for finding HTTP repsons errors. */
  # $full_headers = get_headers($full_domain, 0);
  $status_code = substr($header, 9, (strpos($header, "\n") - 10));
  # $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

  /* Save the terminal URL of any redirected domains as well.
   Responses of "/" should be replaced with just the domain, as it's not redircting. */
  if(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL) != "\\") {
    $final_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
  } else {
    $final_url = strtolower($full_domain);
  }

  /* Now we run out check to see if the page is parked or not. Could be GoDaddy or Network Solutions. */
  if(strpos($body, "godaddy") !== false) {
    $status_desc = "Domain is parked at GoDaddy";
  } elseif(strpos($body, "domainpark") !== false) {
    $status_desc = "Domain is parked at Network Solutions";
  } else {
    $status_desc = "Domain is functional and redirecting";
  }

  /* The response has been recieved and processed. Close the cURL instance. */
  curl_close($ch);
  echo $domain . "," . $status_code . "," . $status_desc . "," . $final_url;
  die();
?>
