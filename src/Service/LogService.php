<?php


namespace App\Service;


class LogService
{
    public function log(string $message) {
        file_put_contents('../../var/log/app_errors.log',$message);
    }
}