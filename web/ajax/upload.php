<?php
/**
 * Created by PhpStorm.
 * User: darkfriend <hi@darkfriend.ru>
 * Date: 18.05.2017
 * Time: 21:12
 */

include_once __DIR__.'/../../vendor/autoload.php';

use Darkfriend\PdfToImage\Config,
    Darkfriend\PdfToImage\Exceptions\PdfExistException;
$config = Config::getInstance();

$error = true;
$msg = '';
$arFileSave = [];

if ($_FILES['file'])
{
    try
    {
        $arFile = $_FILES['file'];
        if($arFile['error']) {
            switch ($arFile['error']) {
                case UPLOAD_ERR_OK :
                    break;
                case UPLOAD_ERR_NO_FILE :
                    throw new Exception('Файл не найден');
                    break;
                case UPLOAD_ERR_INI_SIZE :
                case UPLOAD_ERR_FORM_SIZE :
                    throw new Exception('Размер превышает лимит');
                    break;
                default :
                    throw new Exception('Неизвестная ошибка');
            }
        }

        if ($arFile['size'] > $config->getMaxSize()) {
            throw new Exception('Размер превышает лимит сервера');
        }

        if ($arFile['type'] != 'application/pdf') {
            throw new Exception('Можно загружать только PDF');
        }

        $tempFile = $_FILES['file']['tmp_name'];

        $targetPath = $config->getUploadDir(true);
        if(!is_dir($targetPath)) {
            @mkdir($targetPath,0777,true);
        }
//        $targetFile =  $targetPath. $arFile['name'];
        $fileHash = md5($arFile['name']);
        $fileName = $fileHash.'.pdf';
        $targetFile = $targetPath.$fileName;
        $arFileSave['fileName'] = $fileName;
        $arFileSave['uploadFolder'] = $config->getUploadDir();

        if(file_exists($targetFile)) {
            $error = false;
            $arFileSave['fileExist'] = true;
            throw new Exception('Файл уже загружался');
        }

        if(move_uploaded_file($tempFile,$targetFile)) {
            $error = false;
            $arFileSave['key'] = $fileHash;
        }

    } catch (Exception $e) {
        $msg = $e->getMessage();
    }

} else {
    $msg = 'Массив файлов пуст!';
}

die(json_encode(array_merge([
    'status' => ($error?'error':'success'),
    'msg' => ($error?$msg?$msg:'Неизвестная ошибка':'')
],$arFileSave), JSON_UNESCAPED_UNICODE));