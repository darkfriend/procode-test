<?php
/**
 * Created by PhpStorm.
 * User: darkfriend <hi@darkfriend.ru>
 * Date: 17.05.2017
 * Time: 21:50
 */

$error = true;
$msg = '';
$arResult = [];
session_start();

if($_SERVER['REQUEST_METHOD']=='POST' && $_REQUEST['file']) {
    try {
        $fileHash = $_REQUEST['file'];
        if(!$fileHash) throw new Exception("Хэш файла пуст");
        if(@empty($_SESSION[$fileHash.'_progress'])) {
            throw new Exception("Процесс файла не найден");
        }
        $arResult['progress'] = $_SESSION[$fileHash.'_progress'];
        $error = false;
    } catch (Exception $e) {
        $msg = $e->getMessage();
    }
}

die(json_encode(array_merge([
    'status' => ($error?'error':'success'),
    'msg' => ($error?$msg?$msg:'Неизвестная ошибка':'')
],$arResult), JSON_UNESCAPED_UNICODE));