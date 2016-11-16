
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Jose Franco
 * Date: 18/04/2016
 * Time: 19:54
 *
 * Pagina de registo
 */
?>

<div class="row">
    <div class="col-sm-2">
        <div class="sidebar-nav">
            <div class="navbar navbar-default" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <span class="visible-xs navbar-brand">Menu de perfil</span>
                </div>
                <div class="navbar-collapse collapse sidebar-navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="<?php echo base_url(); ?>index.php/perfil/">Meu perfil</a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/editar/voluntario/">Editar perfil</a></li>
                        <li class="active"><a href="<?php echo base_url(); ?>index.php/Correspondencias">Oportunidades</a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/perfil/logout/">Sair</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>
    </div>
    <div class="col-sm-9">
        <h3><?php echo $funcao; ?></h3>
        <hr>
        <table class="table table-hover">
            <tbody>
                <tr class="info">
                    <td><strong>Distrito</strong></td>
                    <td><?php echo $distrito ?></td>
                </tr>
                <tr class="info">
                    <td><strong>Concelho</strong></td>
                    <td><?php echo $conselho ?></td>
                </tr>
                <tr class="info">
                    <td><strong>Freguesia</strong></td>
                    <td><?php echo $freguesia ?></td>
                </tr>
                <tr class="success">
                    <td><strong>Interesse</strong></td>
                    <td><?php echo $interesse ?></td>
                </tr>
                <tr class="danger">
                    <td><strong>Grupo de atua&ccedil;&atilde;o</strong></td>
                    <td><?php echo $grupo_atuacao ?></td>
                </tr>
                <tr class="warning">
                    <td><strong>Habilita&ccedil;&atilde;o acad&eacute;mica</strong></td>
                    <td><?php echo $habilitacao_academica ?></td>
                </tr>
                <tr class="active">
                    <td><strong>Disponibilidade</strong></td>
                    <td><?php echo ucfirst($disponibilidade) ?></td>
                </tr>
            </tbody>
        </table>
                <a href="<?php echo base_url(); ?>index.php/Correspondencias/instituicao/<?php echo $url ?>">
                <button class="btn btn-lg btn-success btn-wide pull-right">
                    Ir para Instituicao
                </button>

        <hr>
    </div>
</div>
