<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * js_asset()
 *
 * @param    string $filename
 * @param    bool $script default TRUE
 * @param    string $dir default "assets/js" in this site
 * @return    mixed (echo js script tag or return path file js)
 */
function js_asset($filename, $script = TRUE, $dir = 'assets/js')
{
    $path = base_url() . trim($dir, '/') . '/' . $filename;
    return ($script === TRUE) ? '<script type="text/javascript" src="' . $path . '"></script>' . "\n" : $path;
}

/**
 * js_asset_url()
 *
 * @param    string $url
 * @return    string (echo js script tag)
 */
function js_asset_url($url)
{
    return '<script type="text/javascript" src="' . $url . '"></script>' . "\n";
}


/**
 * css_asset()
 *
 * @param    string $filename
 * @param    bool $script default TRUE
 * @param    string $dir default "assets/css" in this site
 * @return    mixed (echo css script tag or return path file css)
 */
function css_asset($filename, $script = TRUE, $dir = 'assets/css')
{
    $path = base_url() . trim($dir, '/') . '/' . $filename;
    return ($script === TRUE) ? '<link type="text/css" rel="stylesheet" href="' . $path . '">' . "\n" : $path;
}

/**
 * css_asset_url()
 *
 * @param    string $url
 * @return    string (echo css script tag)
 */
function css_asset_url($url)
{
    return '<link type="text/css" rel="stylesheet" href="' . $url . '">';
}


/**
 * img_asset()
 *
 * @param    string $filename
 * @param    string $dir default "assets/img" in this site
 * @return    mixed return path file image
 */
function img_asset($filename, $dir = 'assets/img')
{
    return base_url() . trim($dir, '/') . '/' . $filename;
}






/* ######################################################################### */

function slugifyID($str, $replace_sign = '_') {
    $str = trim($str);

    // Replace high ascii characters
    $chars = array("ä", "ö", "ü", "ß");
    $replacements = array("ae", "oe", "ue", "ss");
    $str = str_replace($chars, $replacements, $str);
    $pattern = array("/(é|è|ë|ê)/", "/(ó|ò|ö|ô)/", "/(ú|ù|ü|û)/");
    $replacements = array("e", "o", "u");
    $str = preg_replace($pattern, $replacements, $str);

    // Hyphenate any non alphanumeric characters
    $pattern = array("/[^a-z0-9_.\\-]/i", "/\\+@/i");
    $str = preg_replace($pattern, $replace_sign, $str);

    return $str;
}


function slugify($str, $replace_sign = '-') {
    // Convert to lowercase and remove whitespace
    $str = strtolower(trim($str));

    // Replace high ascii characters
    $chars = array("ä", "ö", "ü", "ß");
    $replacements = array("ae", "oe", "ue", "ss");
    $str = str_replace($chars, $replacements, $str);
    $pattern = array("/(é|è|ë|ê)/", "/(ó|ò|ö|ô)/", "/(ú|ù|ü|û)/");
    $replacements = array("e", "o", "u");
    $str = preg_replace($pattern, $replacements, $str);

    // Remove puncuation
    $pattern = array(":", "!", "?", ".", "/", "'");
    $str = str_replace($pattern, "", $str);

    // Hyphenate any non alphanumeric characters
    $pattern = array("/[^a-z0-9-]/", "/-+/");
    $str = preg_replace($pattern, $replace_sign, $str);

    return $str;
}


function _month($m, $full = false, $lang = 'th') {
    $m = (int) $m;
    if ($full) {
        $month_name = array(
            'th' => array('-', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'),
            'en' => array('-', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec')
        );
    } else {
        $month_name = array(
            'th' => array('-', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'),
            'en' => array('-', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec')
        );
    }
    return $month_name[$lang][$m];
}


function checkISODate($dateStr) {
    if (preg_match('/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/', $dateStr) > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function getMongoDate($dateObject) {
    $timestamp = $dateObject->{'$date'}->{'$numberLong'} / 1000;
    return date("Y-m-d H:i:s", $timestamp);
}


function ISOtoGMT($iso_date, $full = FALSE) {
    if ($full === TRUE) {
        return date('Y-m-d H:i:s', date("U",strtotime($iso_date)));
    }
    return date('Y-m-d H:i', date("U",strtotime($iso_date)));
}


function ISOtoGMTDate($iso_date) {
    return mb_substr(ISOtoGMT($iso_date), 0, 10);
}


function show_date($str_date = 0, $showTime = TRUE, $full = TRUE, $split_text = ' ') {
    if (is_null($str_date) || strpos($str_date, '0000') === 0) {
        return '-';
    }
    if (checkISODate($str_date) === TRUE) {
        $str_date = ISOtoGMT($str_date);
    } else if (is_numeric($str_date)) {
        $str_date = date('Y-m-d H:i', $str_date);
    }

    list($date, $time) = explode(" ", $str_date);
    list($y, $m, $d) = explode("-", $date);
    $text = $d.' '._month($m, $full).' '.(($full) ? $y : substr($y,2,2));
    return $text.(($showTime===TRUE) ? $split_text.substr($time,0,5).' น.' : '');
}



function arrayUnique($array_data, $sensitivity = TRUE) {
    $array_data = array_filter($array_data);
    $array_data = array_map('trim', $array_data);
    if ($sensitivity === TRUE) {
        return array_unique($array_data);
    } else {
        return array_intersect_key($array_data,array_unique(array_map('strtolower', $array_data)));
    }
}


function forceDouble($string = '', $ignore_null = TRUE) {
    $string = (string) $string;
    $string = trim($string);
    if ($ignore_null === TRUE && $string == '') {
        return NULL;
    }
    $string = str_replace(',', '', $string);
    if (empty($string)) {
        return 0;
    }
    return (double) $string;
}


function forceNumber($string = '', $ignore_null = TRUE) {
    $string = (string) $string;
    $string = trim($string);
    if ($ignore_null === TRUE && $string == '') {
        return NULL;
    }
    $string = str_replace(',', '', $string);
    return (int) $string;
}


function title_tag($text, $cut = FALSE) {
    $text = str_replace(array('"', "'"), array('', ""), trim($text));
    $text = htmlspecialchars($text);
    if ($cut !== FALSE) {
        $len = (int) $cut;
        $text = mb_substr($text, 0, $len);
    }
    return $text;
}


function check_empty_value($value) {
    if (is_array($value)) {
        return TRUE;
    } else if (strlen($value) > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}

/*
 * sortByArrayObj
 *  @array $arr : source array of object
 *  @array $arr_sort : 3 desc / 4 asc / ex. ['name' => 4]
 */
function sortByArrayObj(&$arr, $arr_sort = []) {
    $args = [];
    if (!empty($arr)) {
        if (is_object($arr[0])) {
            $temp = json_decode(json_encode($arr), TRUE);
        } else {
            $temp = $arr;
        }

        foreach ($arr_sort as $k => $val) {
            $args[] = array_column($temp, $k);
            $args[] = $val;
        }
        $args[] = &$temp;
        call_user_func_array('array_multisort', $args);
        if (is_object($arr[0])) {
            $arr = json_decode(json_encode($temp));
        } else {
            $arr = $temp;
        }
        unset($temp);
    }
}


function textNumber($number, $precision = 1) {
    if (check_empty_value($number) === FALSE) {
        return 'N/A';
    }
    if ($number < 1000) {
        return number_format($number, 0);
    } else if ($number < 1000000) {
        return number_format($number / 1000, $precision) . ' K';
    } else if ($number < 1000000000) {
        return number_format($number / 1000000, $precision) . ' M';
    }

    return number_format($number / 1000000000, $precision) . ' B';
}


function get_ip() {
    if (getenv('HTTP_CLIENT_IP')) {
        $ip_address = getenv('HTTP_CLIENT_IP');
    } else if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip_address = getenv('HTTP_X_FORWARDED_FOR');
    } else if(getenv('HTTP_X_FORWARDED')) {
        $ip_address = getenv('HTTP_X_FORWARDED');
    } else if(getenv('HTTP_FORWARDED_FOR')) {
        $ip_address = getenv('HTTP_FORWARDED_FOR');
    } else if(getenv('HTTP_FORWARDED')) {
        $ip_address = getenv('HTTP_FORWARDED');
    } else if(getenv('REMOTE_ADDR')) {
        $ip_address = getenv('REMOTE_ADDR');
    } else {
        $ip_address = 'UNKNOWN';
    }
    return $ip_address;
}


function genRandomString($length = 8, $notSpecial = FALSE) {
    $characters = ($notSpecial) ? '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' : '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}


function replaceSpecialText($text, $convert = FALSE)
{
    if ($convert === TRUE) {
        $output = str_replace(array('-', '_'), array('/', '+'), $text);
        $mod4 = strlen($output) % 4;
        if ($mod4) {
            $output .= substr('====', $mod4);
        }
    } else {
        $output = rtrim($text, '=');
        $output = str_replace(array('/', '+'), array('-', '_'), $output);
    }
    return $output;
}


function _encodeData($data, $password) {
    if (is_array($data)) {
        $data = json_encode($data);
    }
    $salt = substr(md5(mt_rand(), true), 8);
    $key = md5($password . $salt, true);
    $suffix = md5($key . $password . $salt, true);
    $code = openssl_encrypt($data, 'AES-128-CFB8', $key, TRUE, $suffix);
    $output = base64_encode($salt . 'OS_ENCODE' . $code);
    return replaceSpecialText($output);
}


function _decodeData($data, $password)
{
    $data = replaceSpecialText($data, TRUE);
    $data = base64_decode($data);
    $salt = substr($data, 0, 8);
    $code = substr($data, 17);
    $key = md5($password . $salt, true);
    $suffix = md5($key . $password . $salt, true);
    return openssl_decrypt($code, 'AES-128-CFB8', $key, TRUE, $suffix);
}


function checkPermission($MY_PERMISSION, $check_slug, $level = 'view') {
    if (!empty($MY_PERMISSION->level) && $MY_PERMISSION->level == 9) {
        return TRUE;
    }

    $result = FALSE;
    $check_slug = (is_array($check_slug)) ? $check_slug : [$check_slug];

    foreach ($check_slug as $check) {
        if (!empty($MY_PERMISSION)) {
            if (isset($MY_PERMISSION->{$check})) {
                $permission = $MY_PERMISSION->{$check};
                switch ($level) {
                    case 'delete': $result = (strpos($permission, '1', 0) === 0);
                        break;
                    case 'add': $result = (strpos($permission, '1', 1) === 1);
                        break;
                    case 'edit': $result = (strpos($permission, '1', 2) === 2);
                        break;
                    case 'view': $result = TRUE;
                        break;
                }
            }
            if ($result) { break; }
        }
    }

    return $result;
}

function checkMakeDir($dir) {
    $dir = trim($dir, '/');
    if (!is_dir(PATH_UPLOAD . $dir) && !file_exists(PATH_UPLOAD . $dir)) {
        mkdir(PATH_UPLOAD . $dir, 0755, TRUE);
    }

    return PATH_UPLOAD . $dir . '/';
}

function getYoutubeSlugURL($url) {
    preg_match('%(?:youtube\.com/([^\s\/\?]+)/([^\s\/\?]+))%i', $url, $result);
    if (!empty($result[1]) && !empty($result[2])) {
        return (object) [ 'id' => rawurldecode($result[2]), 'type' => $result[1] ];
    }

    return FALSE;
}


function thaiBath($amount_number)
{
    $amount_number = number_format($amount_number, 2, ".","");
    $pt = strpos($amount_number , ".");
    $fraction = "";
    if ($pt === false) {
        $number = $amount_number;
    } else {
        $number = substr($amount_number, 0, $pt);
        $fraction = substr($amount_number, $pt + 1);
    }

    $ret = "";
    $baht = ReadNumber ($number);
    if ($baht != "")
        $ret .= $baht . "บาท";

    $satang = ReadNumber($fraction);
    if ($satang != "")
        $ret .=  $satang . "สตางค์";
    else
        $ret .= "ถ้วน";
    return $ret;
}


function ReadNumber($number) {
    $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
    $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
    $number = $number + 0;
    $ret = "";
    if ($number == 0) return $ret;
    if ($number > 1000000)
    {
        $ret .= ReadNumber(intval($number / 1000000)) . "ล้าน";
        $number = intval(fmod($number, 1000000));
    }

    $divider = 100000;
    $pos = 0;
    while($number > 0)
    {
        $d = intval($number / $divider);
        $ret .= (($divider == 10) && ($d == 2)) ? "ยี่" :
            ((($divider == 10) && ($d == 1)) ? "" :
                ((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
        $ret .= ($d ? $position_call[$pos] : "");
        $number = $number % $divider;
        $divider = $divider / 10;
        $pos++;
    }
    return $ret;
}


function formatTaxNo($tax_no) {
    $taxStr = trim(str_replace([' ', '-'], ['', ''], $tax_no));

    $format = $taxStr[0] .'-'. substr($taxStr, 1, 4) .'-'. substr($taxStr, 5, 5) .'-'. substr($taxStr, 10, 2) .'-'. $taxStr[12];

    return $format;
}
