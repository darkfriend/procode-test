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
    public $keySession = 'pdfToImg';

    public function __construct($pdfFile, $keySession=null)
    {
        parent::__construct($pdfFile);
        if(!$keySession) $keySession = md5(time());
        $this->keySession = $keySession;
    }

    /**
     * Процесс сохранения каждой странице на диске
     *
     * @param string $directory
     * @param string $prefix
     *
     * @return array $files the paths to the created images
     */
    public function processSave($directory, $prefix='page')
    {
        $numberOfPages = $this->getNumberOfPages();
//        $directory = $_SERVER['DOCUMENT_ROOT'].'/uploads/jpg/'.$directory;
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