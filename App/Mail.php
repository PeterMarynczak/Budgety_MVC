<?php

namespace App;

use App\Config;

/**
 * Mail
 *
 * PHP version 7.0
 */
class Mail
{

    /**
     * Send a message
     *
     * @param string $to Recipient
     * @param string $subject Subject
     * @param string $text Text-only content of the message
     * @param string $html HTML content of the message
     *
     * @return mixed
     */   
    public static function sendLabEmail($to, $subject, $text, $html)
    {
        //Initialization of CURL library
        $curl = curl_init();
        //Setting the address from which data will be collected
        $url = "https://api.emaillabs.net.pl/api/new_sendmail";

        //Setting App Key
        $appkey = Config::labAPP_KEY; 
        //Setting Secret Key
        $secret = Config::labSECRET_KEY;  

        //Creating criteria of dispatch      
         $data = array(
             'to' => array (
             $to => ""
            ),
             'smtp_account' => '1.pmarynczak.smtp',
             'subject' => $subject, 
             'html' => $html,
             'txt' => $text,
             'from' => 'piotr.marynczak.programista@gmil.com'
         );

        //Setting POST method
        curl_setopt($curl, CURLOPT_POST, 1);
        //Transfer of data to POST
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        //Setting the authentication type
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD , "$appkey:$secret");
        //Transfer URL action
        curl_setopt($curl, CURLOPT_URL, $url);
        //Settings of the return from the server
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        $result = curl_exec($curl);
    }
}
