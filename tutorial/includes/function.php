<?php
if (!defined('_INCODE')) die('access deined ...');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
function layout($layoutName='header', $dir='', $data = []) {
    if (!empty($dir)) {
        $dir = '/'.$dir;
    }
    if (file_exists(_WEB_PATH_TEMPLATE.$dir.'/layouts/' .$layoutName. '.php')) {
        require_once _WEB_PATH_TEMPLATE.$dir.'/layouts/' .$layoutName. '.php';
    }
}

function sendEmail($to, $subject, $content) {
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'hoanggutar@gmail.com';                     //SMTP username
        $mail->Password   = 'gutscnvptgczmlgv';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;

        //Recipients
        $mail->setFrom('hoanggutar@gmail.com', 'Mailer');
        $mail->addAddress($to);     //Add a recipient

        //Content
        $mail->isHTML(true);     
        $mail->CharSet = 'UTF-8';                             //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        return $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// check method post
function isPost() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return true;
    }
    
    return false;
}

// check method get
function isGet() {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        return true;
    }

    return false;
}

// get value of method post, get
function getBody($method='') {
    $bodyArr = [];

    if (empty($method)) {
        if (isGet()) {
            /* read key of arr $_GET */
            if (!empty($_GET)) {
                foreach ($_GET as $key=>$value) {
                    $key = strip_tags($key);
                    if (is_array($value)) {
                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
        
        if (isPost()) {
            // read key of arr $_POST
            if (!empty($_POST)) {
                foreach($_POST as $key=>$value) {
                    $key = strip_tags($key);
                    if (is_array($value)) {
                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
    } else {
        if ($method == 'get') {
            if (!empty($_GET)) {
                foreach ($_GET as $key=>$value) {
                    $key = strip_tags($key);
                    if (is_array($value)) {
                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        } elseif ($method == 'post') {
            if (!empty($_POST)) {
                foreach($_POST as $key=>$value) {
                    $key = strip_tags($key);
                    if (is_array($value)) {
                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
    }

    return $bodyArr;
}

// validation email
function isEmail($email) {
    $checkEmail = filter_var($email. FILTER_VALIDATE_EMAIL);
    return $checkEmail;
}

// check int
function isNumberInt($number, $range=[]) {
    if (!empty($range)) {
        $option = ['options'=>$range];
        $checkNumber = filter_var($number, FILTER_VALIDATE_INT, $option);
    } else {
        $checkNumber = filter_var($number, FILTER_VALIDATE_INT);
    }

    return $checkNumber;
}

// check float
function isNumberIntfFloat($number, $range=[]) {
    if (!empty($range)) {
        $option = ['options'=>$range];
        $checkNumber = filter_var($number, FILTER_VALIDATE_FLOAT, $option);
    } else {
        $checkNumber = filter_var($number, FILTER_VALIDATE_FLOAT);
    }

    return $checkNumber;
}

// check phone
function isPhone($phone) {
    $checkFirstZero = false;
    if ($phone[0] === '0') {
        $checkFirstZero = true;
        $phone = substr($phone, 1);
    }

    $checkNumberLast = false;
    if (isNumberInt($phone) && strlen($phone) == 9) {
        $checkNumberLast = true;
    } 

    if (($checkFirstZero && $checkNumberLast)) {
        return true;
    }

    return false;
}

// alert msg
function getMsg($msg, $type='success') {
    if (!empty($msg)) {
        echo '<div class="alert alert-'.$type.'" role="alert">';
        echo $msg;
        echo '</div>';
    }
}

// navigation
function redirect($path = 'index.php') {
    echo $path;
    header("Location: $path");
    exit;
}

// hàm thông báo lỗi
function form_error($fileName, $errors, $beforeHtml='', $afterHtml='') {
    return (!empty($errors[$fileName]) ? $beforeHtml.reset($errors[$fileName]).$afterHtml : null);
}

// hàm get old value
function getOldValue($old, $fileName, $default=null) {
    return (!empty($old[$fileName])) ? $old[$fileName] : $default;
}

function isLogin() {
    $checkLogin = false;
    $tokenLogin = getSession('loginToken');
    $queryToken = firstRaw("SELECT user_id FROM login_token WHERE token='$tokenLogin'");
    if (!empty($queryToken)) {
        $checkLogin = $queryToken;
    } else {
        $checkLogin = false;
    }
    
    return $checkLogin;
}

// auto logout settime out 15'
function autoRemoveTokenLogin() {
    $allUser = getRaw("SELECT * FROM users WHERE status=1");
    if (!empty($allUser)) {
        foreach ($allUser as $user) {
            $now = date('Y-m-d H:i:s');
            $before = $user['last_activity'] ?? '';
            $diff = strtotime($now)-strtotime($before);
            $diff = floor($diff/60);

            if ($diff >= 1) {
                delete('login_token', "user_id=".$user['id']);
            }
        }
    }
}

// lưu thời gian đăng nhập cuối cùng của user
function saveActivity() {
    $userId = isLogin()['user_id'];
    update('users', ['last_activity'=>date('Y-m-d H:i:s')], "id=$userId");
}

function setActiveSidebar ($module) {
    if ($_GET['module'] === $module) {
        return true;
    }
    
    return false;
}

/**
 * get info
 * @param $id
 */
function getInfo ($id) {
    $sql =  "SELECT * FROM users WHERE id=".$id;
    return getRaw($sql);
}

function getLinkAdmin ($module, $action = '', $params = []) {
    $url = _WEB_HOST_ROOT_ADMIN;
    $url = $url.'/?module='.$module;
    
    if (!empty($action)) {
        $url = $url.'&action='.$action;
    }

    /**
     * params = ['id' => 1, 'keyword' => 'example']
     * => paramsString = id=1&keyword=example
     */
    if (!empty($params)) {
        $paramsString = http_build_query($params);
        $url = $url.'&'.$paramsString;
    }

    return $url;
}

// format date
function getDateFormat ($strDate, $format) {
    $dateObject = date_create($strDate);
    if (!empty($dateObject)) {
        return date_format($dateObject, $format);
    } else {
        return false;
    }
}