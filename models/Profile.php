<?php


namespace app\models;


use app\components\CoreProxy;

class Profile
{
    public static function getBalance()
    {
        $balance = json_decode(CoreProxy::getBalance()->content, true)['acc_base'];
        return $balance;
    }
}