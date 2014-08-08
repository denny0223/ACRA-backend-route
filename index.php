<?php
    if (empty($_POST)) {
      header("HTTP/1.0 404 Not Found");
      die();
    }

    // Outputs all POST parameters to a text file. The file name is the date_time of the report reception
    $fileName = @date('Y-m-d_H-i-s').'.txt';
    $file = fopen($fileName,'w') or die('Could not create report file: ' . $fileName);
//    $oriPost = 'data.txt';
//    $postFile = fopen($oriPost,'w') or die('Could not create report file: ' . $oriPost);

// curl -v -A "Android" -H "Content-Type: application/x-www-form-urlencoded" -H"Accept: text/html,application/xml,application/json,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5" -H"Connection: Keep-Alive" --data-binary @data.txt http://your.backend.host/acra/

    $hosts = array(
      'http://your.backend.host1/acra/',
      'http://your.backend.host2/acra/'
    );

    $handle  = array();

    $mh = curl_multi_init();

    foreach($hosts as $host) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $host);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Android');
        $header[] = 'Connection: Keep-Alive';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);

        curl_multi_add_handle($mh, $ch);

        $handle[] = $ch;
    }

    do {
        curl_multi_exec($mh, $running);
        curl_multi_select($mh);
    } while ($running > 0);

    foreach($handle as $ch) {
        curl_multi_remove_handle($mh, $ch);
    }

    curl_multi_close($mh);

    foreach($_POST as $key => $value) {
        $reportLine = $key." = ".$value."\n";

        fwrite($file, $reportLine) or die ('Could not write to report file ' . $reportLine);

//        $data = urlencode($key)."=".urlencode($value)."&";
//        fwrite($postFile, $data) or die ('Could not write to report file ' . $data);
    }
    fclose($file);
?>

