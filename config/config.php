<?php

$este_directorio = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));
$directorio_padre = dirname($este_directorio);
define("DOC_ROOT", $directorio_padre . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);