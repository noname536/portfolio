<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Jose Franco
 * Date: 18/04/2016
 * Time: 19:54
 *
 * Template navigator para o website, todas as paginas fazem load deste html
 * Este e o navigator default quando esta logado no website
 *
 */
?>

<body>
    <div class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav_collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url(); ?>index.php/home/">Volunteer@FC.UL</a>
            </div>
            <div class="collapse navbar-collapse nav_collapse">
                <ul class="nav navbar-nav navbar-lefte">
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
                    <li><a href="<?php echo base_url(); ?>index.php/perfil/">Perfil</a></li>
                </ul>
            </div>
        </div>
    </div>
    <hr>