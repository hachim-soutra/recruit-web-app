<?php

namespace App\Library;

use Illuminate\Support\Facades\Auth;

class PushNotification
{
    public static function fire_notification($param)
    {

        $fpm_token = Auth::user()->fpm_token;

        $token           = $param['token'];
        $app_id          = "098db30d-0f07-47c2-b9e8-160710c55eff";
        $OS_REST_API_KEY = "YzUyMzUzODgtYjA5OS00MTdiLTgxMTUtMWY0MWIwMzlmZWRj";
        $url             = "https://onesignal.com/api/v1/notifications";
        $content         = array(
            "en" => $param['msg'],
        );
        $fields = array(
            'app_id'             => $app_id,
            'include_player_ids' => array($token),
            'contents'           => $content,
        );

        $fields = json_encode($fields);
        //return $fields;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . $OS_REST_API_KEY));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;

    }

    public function sendFirebaseNotification($toIDs, $titleMessage, $bodyMessage, $username, $profile_url)
    {

        // $api_secret_key = "key=AAAAZiJqMYE:APA91bEDE_6RdUZy7ewGAb3dCpOKrhMb7_5d-vDUL4Mt6g9nNsiDh32gNxUzHWAj6tl4bmoOJJwCbhkcLS6-uNvqNWiPBUNCMvtY3YuzsVsEI9KHyqBopuQDdc1fSuDqr0UuQty11Z5d";

        $api_secret_key = "AAAAZiJqMYE:APA91bEDE_6RdUZy7ewGAb3dCpOKrhMb7_5d-vDUL4Mt6g9nNsiDh32gNxUzHWAj6tl4bmoOJJwCbhkcLS6-uNvqNWiPBUNCMvtY3YuzsVsEI9KHyqBopuQDdc1fSuDqr0UuQty11Z5d";

        $registrationIds = $toIDs;

        $msg = array(
            'body'         => $bodyMessage,
            'title'        => $titleMessage,
            'icon'         => 'myicon',
            'sound'        => 'mySound',
            'notify_for'   => $titleMessage,
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        );

        $iosdata = array(
            'body'            => $bodyMessage,
            'title'           => $titleMessage,
            'click_action'    => 'FLUTTER_NOTIFICATION_CLICK',
            "sound"           => "default",
            "badge"           => 0,
            'mutable_content' => true,
        );

        $data = array(
            'notify_for'   => '',
            'username'     => $username,
            'profile_url'  => $profile_url,
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        );

        $newFormat = array(
            'to'           => $registrationIds,
            'notification' => $iosdata,
            'data'         => $data,
        );

        $headers = array(
            //'Authorization: key=' . $api_secret_key,
            'Authorization:' . $api_secret_key,
            'Content-Type: application/json',
        );

        //Send Reponse To FireBase Server

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($newFormat));

        $result = curl_exec($ch);

        curl_close($ch);

        //Echo Result Of FireBase Server

        return $result;

    }

}
