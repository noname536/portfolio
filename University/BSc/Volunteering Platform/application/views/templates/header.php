<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Jose Franco
 * Date: 18/04/2016
 * Time: 19:54
 *
 * Template header para o website, todas as paginas fazem load deste html
 * Basicamente o header faz load do <head></head>
 *
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="<?php echo base_url(); ?>bootstrap-flatui/js/vendor/jquery.min.js"></script>

    <!-- Loading Bootstrap -->
    <link href="<?php echo base_url(); ?>bootstrap-flatui/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>bootstrap-flatui/css/flat-ui.min.css" rel="stylesheet" type="text/css">

    <!-- Loading my Styles -->
    <link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet" type="text/css">
</head>