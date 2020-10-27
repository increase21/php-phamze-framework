<?php

class helper
{
    public static function Output_Error($code = null, $message = null)
    {
        header('Content-Type:application/json');
        if (isset($code)) {
            if (is_int($code)) {
                switch ($code) {
                    case 400: $message = !is_null($message) ? $message : 'Bad Request';
                    break;
                    case 401: $message = !is_null($message) ? $message : 'Unauthorized';
                    break;
                    case 404:  $message = !is_null($message) ? $message : 'Requested resource not found';
                    break;
                    case 405:  $message = !is_null($message) ? $message : 'Method Not Allowed';
                    break;
                    case 500:  $message = !is_null($message) ? $message : 'Whoops! somthing went wrong, our engineers have been notified';
                  break;
                    default:
                    isset($message) ? $message : '';
                }
                http_response_code($code);
            }
        }
        $response = [];
        if (!is_null($code)) {
            $response['code'] = $code;
        }
        $response['error'] = $message;
        echo json_encode($response);
    }

    public static function Output_Success($data)
    {
        header('Content-Type:application/json');
        echo json_encode($data);
    }

    public static function SendMail($message, $to, $subject, $isHTML = null)
    {
        include_once __DIR__.'/../phpmailer/PHPMailerAutoload.php';

        $AppMail = new PHPMailer(true);
        try {
            $AppMail->isSMTP(); // Send using SMTP
            $AppMail->Host = ''; // Set the SMTP server to send through
            $AppMail->SMTPAuth = true; // Enable SMTP authentication
            $AppMail->SMTPSecure = 'tls'; //Enable TLS authentication
            $AppMail->SMTPDebug = SMTP::DEBUG_SERVER;
            $AppMail->Username = ''; // SMTP username
            $AppMail->Password = ''; // SMTP password
               // $AppMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $AppMail->Port = 25; // TCP port to connect to
               //Recipients
            $AppMail->setFrom('no-reply@admin.com', 'Admin Limited');   // Add Sender
            $AppMail->addAddress($to);
            // Content
            $AppMail->isHTML($isHTML === true ?: false); // Set email format to HTML
            $AppMail->Body = $body;
            $AppMail->send();

            return 'success';
        } catch (Exception $e) {
            if (APP_DEBUG === true) {
                // echo 'error '.self::$AppMail->ErrorInfo;
                return $e;
            } else {
                return 'error';
            }
        }
    }
}
