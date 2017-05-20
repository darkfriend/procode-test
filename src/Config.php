<?php
/**
 * Created by PhpStorm.
 * User: darkfriend <hi@darkfriend.ru>
 * Date: 18.05.2017
 * Time: 21:33
 */

namespace Darkfriend\PdfToImage;


class Config
{
    private static $instance;

    private function __construct() {}

    /**
     * Singleton instance
     * @return Config
     */
    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Возвращает максимальный размер загружаемых файлов
     * @param string|bool $selectType возвращаемый тип G|M|K|false.
     * При false возвращает в байтах
     * @return int
     */
    public function getMaxSize($selectType=false) {
        $systemMaxSize = ini_get('post_max_size');
        $type = false;
        preg_match('#(\d+)(\D+)#',$systemMaxSize,$matchType);
        if($matchType) {
            if(isset($matchType[1])) $systemMaxSize=$matchType[1];
            if(isset($matchType[2])) $type=$matchType[2];
        }
        switch ($type) {
            case 'G' :
                $maxSize = $systemMaxSize*pow(1024,3);
                break;
            case 'M' :
                $maxSize = $systemMaxSize*pow(1024,2);
                break;
            case 'K' :
                $maxSize = $systemMaxSize*1024;
                break;
            default :
                $maxSize = $systemMaxSize;
        }
        if($selectType) {
            switch ($selectType) {
                case 'G' :
                    $maxSize /= pow(1024, 3);
                    break;
                case 'M' :
                    $maxSize /= pow(1024, 2);
                    break;
                case 'K' :
                    $maxSize /= 1024;
                    break;
            }
            if(!is_integer($maxSize)) $maxSize = number_format($maxSize, 2, '.', '');
        }
        return $maxSize;
    }

    public function getUploadDir($abs=false) {
        $uploadFolder = 'uploads/pdf';
        if($abs) $uploadFolder = $_SERVER['DOCUMENT_ROOT'] . '/'. $uploadFolder . '/';
        return $uploadFolder;
    }

    public function getJpegDir($abs=false) {
        $uploadFolder = 'uploads/jpeg';
        if($abs) $uploadFolder = $_SERVER['DOCUMENT_ROOT'] . '/'. $uploadFolder . '/';
        return $uploadFolder;
    }
}