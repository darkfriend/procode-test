<?php
/**
 * Created by PhpStorm.
 * User: darkfriend <hi@darkfriend.ru>
 * Date: 17.05.2017
 * Time: 21:52
 */

include_once __DIR__.'/../../vendor/autoload.php';

use Darkfriend\PdfToImage\Config,
    Darkfriend\PdfToImage\Exceptions\PdfExistException,
    Darkfriend\PdfToImage\PdfParse;
$config = Config::getInstance();
session_start();

$error = true;
$msg = '';
$arFileSave = [];
//$_SERVER['REQUEST_METHOD']=='POST' &&
//die($_REQUEST['file']);
if($_REQUEST['file']) {
    try {
        $fileHash = $_REQUEST['file'];
        if(!$fileHash) throw new Exception("Хэш файла пуст");
        $filePath = $config->getUploadDir(true).$fileHash.'.pdf';
        if(!file_exists($filePath)) throw new Exception("Файл не найден");
        $savePath = $config->getJpegDir(true).$fileHash.'/';
        if(!is_dir($savePath)) {
            if(!@mkdir($savePath,0777,true))
                throw new Exception("Не смог создать папку для {$fileHash}");
        }
        $pdfParser = new PdfParse($filePath,$fileHash);
        $arFileSave['files'] = $pdfParser->processSave($savePath);
        $error = false;
    } catch (Exception $e) {
        $msg = $e->getMessage();
    }
}

die(json_encode(array_merge([
    'status' => ($error?'error':'success'),
    'msg' => ($error?$msg?$msg:'Неизвестная ошибка':'')
],$arFileSave), JSON_UNESCAPED_UNICODE));
?>