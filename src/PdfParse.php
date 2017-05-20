<?php
/**
 * Created by PhpStorm.
 * User: darkfriend <hi@darkfriend.ru>
 * Date: 17.05.2017
 */

namespace Darkfriend\PdfToImage;

use Spatie\PdfToImage\Pdf;

class PdfParse extends Pdf
{
    public $keySession;

    public function __construct($pdfFile, $keySession=null) {
        parent::__construct($pdfFile);
        if(!$keySession) $keySession = md5(time());
        $this->keySession = $keySession;
    }

    /**
     * Процесс сохранения каждой странице на диске
     * @param string $directory - путь до директории сохранения
     * @param string $prefix - префикс для постраничных картинок
     * @return array $files относительные пути до файлов
     */
    public function processSave($directory, $prefix='page') {
        $numberOfPages = $this->getNumberOfPages();
        if ($numberOfPages === 0)
            return [];

        $directory = rtrim($directory, '/');
        return array_map(function ($pageNumber) use ($directory, $prefix, $numberOfPages) {
            $this->setPage($pageNumber);
            $destination = "{$directory}/{$prefix}{$pageNumber}.{$this->outputFormat}";
            $this->saveImage($destination);
            $_SESSION[$this->keySession] = $pageNumber;
            $_SESSION[$this->keySession.'_progress'] = floor((100/$numberOfPages)*$pageNumber);
            return str_replace($_SERVER['DOCUMENT_ROOT'],'',$destination);
        }, range(1, $numberOfPages));
    }
}