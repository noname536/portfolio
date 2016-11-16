
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
                        <li><a href="<?php echo base_url(); ?>index.php/editar/instituicao/">Editar perfil</a></li>
                        <li class="active"><a href="<?php echo base_url(); ?>index.php/perfil/oportunidades/">Oportunidades</a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/correspondencias" class="active">Voluntarios <span class="badge"><?php echo $numCorrs; ?></span></a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/perfil/logout/">Sair</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>
    </div>
    <div class="col-sm-9">
        <h3>Oportunidades de <?php echo $nome ?></h3>
        <hr>
        <table class="table table-hover">
            <tbody>
                <?php foreach($oportunities as $op) { ?>
                <tr class="active">
                    <td><strong><?php echo $op['nome']; ?></strong></td>
                    <td>
                        <a href="<?php echo base_url(); ?>index.php/perfil/remove_oportunidade/<?php echo $op['url']; ?>">
                            <button id="submit_criacao_oportunidade" class="btn btn-sm btn-danger btn-embossed pull-right">
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                        </a>
                        <a href="<?php echo base_url(); ?>index.php/perfil/editar_oportunidade/<?php echo $op['url']; ?>">
                            <button id="submit_criacao_oportunidade" class="btn btn-sm btn-warning btn-embossed pull-right">
                                <span class="glyphicon glyphicon-edit"></span>
                            </button>
                        </a>
                        <a href="<?php echo base_url(); ?>index.php/perfil/oportunidade/<?php echo $op['url']; ?>">
                            <button id="submit_criacao_oportunidade" class="btn btn-sm btn-info btn-embossed pull-right">
                                <span class="glyphicon glyphicon-eye-open"></span>
                            </button>
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <hr>
        <a href="<?php echo base_url(); ?>index.php/perfil/nova_oportunidade/">
            <button id="submit_criacao_oportunidade" class="btn btn-md btn-success btn-wide btn-embossed">
                Nova <span class="glyphicon glyphicon-plus"></span>
            </button>
        </a>
        <hr>
    </div>
</div>
