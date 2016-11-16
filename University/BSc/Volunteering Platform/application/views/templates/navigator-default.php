<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Jose Franco
 * Date: 18/04/2016
 * Time: 19:54
 *
 * Template navigator para o website, todas as paginas fazem load deste html
 * Este e o navigator default quando ninguem esta logado no website
 *
 */
?>

<body>
    <div class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="<?php echo base_url(); ?>index.php/home/"><?php echo $websiteTitle; ?></a>
            </div>
            <ul class="nav navbar-nav navbar-left">
                <li class="active"><a href="<?php echo base_url(); ?>index.php/home/">Homepage</a></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Informa&ccedil;&otilde;es<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url(); ?>index.php/home/faculdade">Faculdade</a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/home/cadeira">Cadeira</a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/home/alunos">Alunos</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?php echo base_url(); ?>index.php/registo/">Registrar</a></li>
                <li><a href="<?php echo base_url(); ?>index.php/login/">Entrar</a></li>
            </ul>
        </div>
    </div>
    <hr>