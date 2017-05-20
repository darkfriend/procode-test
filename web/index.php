<?php
/**
 * Created by PhpStorm.
 * User: darkfriend <hi@darkfriend.ru>
 * Date: 17.05.2017
 */

include_once __DIR__.'/../vendor/autoload.php';
session_start();
use Darkfriend\PdfToImage\Config;
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PROCODE TEST</title>
    <link rel="stylesheet" href="bower_components/dropzone/dist/min/dropzone.min.css">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <div class="header">
        <h3 class="text-muted">PROCODE TEST</h3>
    </div>
    <div class="jumbotron">
        <form action="ajax/upload.php" method="post" id="ddForm" enctype="multipart/form-data" class="dropzone"></form>
    </div>
    <div class="row" id="fileList">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Список файлов</h3>
            </div>
            <div class="panel-body">
                <ul id="listGroup" class="list-group"></ul>
            </div>
        </div>
    </div>
</div>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/dropzone/dist/min/dropzone.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>