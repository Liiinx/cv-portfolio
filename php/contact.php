<?php

require 'recaptchalib.php';
//$siteKey = '6Lc6TJUUAAAAAFs4y5MYlJezrHdyS02JOQMCsCSF'; // votre clé publique
$secret = 'votre clé privée'; // votre clé privée


$array = ["firstname" => "", "name" => "", "email" => "", "phone" => "", "message" => "",
    "firstnameError" => "", "nameError" => "", "emailError" => "", "phoneError" => "", "messageError" => "",
    "captchaError" => "", "isSuccess" => false];

$emailTo = "felix.tuffreaud@laposte.net";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $array["firstname"] = verifyInput($_POST['firstname']);
    $array["name"] = verifyInput($_POST['name']);
    $array["email"] = verifyInput($_POST['email']);
    $array["phone"] = verifyInput($_POST['phone']);
    $array["message"] = verifyInput($_POST['message']);
    $array["isSuccess"] = true;
    $emailText = "";

    if (empty($array["firstname"])) {
        $array["firstnameError"] = "Merci de renseigner ton prénom !";
        $array["isSuccess"] = false;
    } else {
        $emailText .= "Firstname: {$array["firstname"]}\n";
    }
    if (empty($array["name"])) {
        $array["nameError"] = "Ton nom aussi !";
        $array["isSuccess"] = false;
    } else {

        $emailText .= "Name: {$array["name"]}\n";
    }
    if (!isEmail($array["email"])) {
        $array["emailError"] = "Merci d'indiquer un email valide";
        $array["isSuccess"] = false;
    } else {
        $emailText .= "Email: {$array["email"]}\n";
    }
    if (!isPhone($array["phone"])) {
        $array["phoneError"] = "Seulement des chiffres et des espaces";
        $array["isSuccess"] = false;
    } else {
        $emailText .= "Phone: {$array["phone"]}\n";
    }

    if (empty($array["message"])) {
        $array["messageError"] = "Tu peux indiquer ton message ici !";
        $array["isSuccess"] = false;
    } else {
        $emailText .= "Message: {$array["message"]}\n";
    }

    //captcha

    $reCaptcha = new ReCaptcha($secret);
    if(isset($_POST["g-recaptcha-response"])) {
        $resp = $reCaptcha->verifyResponse(
            $_SERVER["REMOTE_ADDR"],
            $_POST["g-recaptcha-response"]
        );
        if ($resp != null && $resp->success) {
            //echo "CAPTCHA OK";
            //ne rien faire
        } else {
            // ajouter le message d'erreur du captcha
            $array["captchaError"] = "merci de valider le captcha";
            $array["isSuccess"] = false;
        }
    }

    // en cas de success envoyer le mail
    if ($array["isSuccess"]) {
        //envoie de l'email
        $headers = "From: {$array["firstname"]} {$array["name"]} <{$array["email"]}>\r\nReply-To: {$array["email"]}";
        mail($emailTo, "Un message de mon site", $emailText, $headers);
    }

    echo json_encode($array);
}

function isPhone($var) {
    return preg_match("/^[0-9 ]*$/", $var);
}

function isEmail($var) {
    return filter_var($var, FILTER_VALIDATE_EMAIL);
}

function verifyInput($var) {
    $var = trim($var);
    $var = stripcslashes($var);
    $var = htmlspecialchars($var);
    return $var;
}