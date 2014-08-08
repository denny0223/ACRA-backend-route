<?php
    // Outputs all POST parameters to a text file. The file name is the date_time of the report reception
    $fileName = date('Y-m-d_H-i-s').'.txt';
    $file = fopen($fileName,'w') or die('Could not create report file: ' . $fileName);
//    $oriPost = 'data.txt';
//    $postFile = fopen($oriPost,'w') or die('Could not create report file: ' . $oriPost);

// curl -v -A "Android" -H "Content-Type: application/x-www-form-urlencoded" -H"Accept: text/html,application/xml,application/json,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5" -H"Connection: Keep-Alive" --data-binary @data.txt http://your.backend.host/acra/

    $host = 'http://your.backend.host/acra/';

    curl_setopt($ch, CURLOPT_URL, $host);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Android');
    $header[] = 'Connection: Keep-Alive';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);

    curl_exec($ch);

    foreach($_POST as $key => $value) {
        $reportLine = $key." = ".$value."\n";

        fwrite($file, $reportLine) or die ('Could not write to report file ' . $reportLine);

//        $data = urlencode($key)."=".urlencode($value)."&";
//        fwrite($postFile, $data) or die ('Could not write to report file ' . $data);
    }
    fclose($file);
?>

