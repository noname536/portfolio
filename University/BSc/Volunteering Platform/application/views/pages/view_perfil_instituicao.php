
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
                            <li class="active"><a href="<?php echo base_url(); ?>index.php/perfil/">Meu perfil</a></li>
                            <li><a href="<?php echo base_url(); ?>index.php/editar/instituicao/">Editar perfil</a></li>
                            <li><a href="<?php echo base_url(); ?>index.php/perfil/oportunidades/">Oportunidades</a></li>
                            <li><a href="<?php echo base_url(); ?>index.php/correspondencias" class="active">Voluntarios <span class="badge"><?php echo $numCorrs; ?></span></a></li>
                            <li><a href="<?php echo base_url(); ?>index.php/perfil/logout/">Sair</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>
        <div class="col-sm-9">
            <div class="container">
                <h3><?php echo $nome ?></h3>
                    <div class="col-md-2">
                        <img src="<?php echo base_url(); ?>/res/uploads/instituicao_default.png" class="img-responsive" >
                    </div>
                <div class="row">
                    <div class="col-lg-8">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <td><strong>Nome Instituicao</strong></td>
                                <td><?php echo $nome ?></td>   <!-- fazer model para nome etc etc etc -->
                            </tr>
                            <tr>
                                <td><strong>Telefone</strong></td>
                                <td><?php echo $telefone ?></td>
                            </tr>
                            <tr>
                                <td><strong>Distrito</strong></td>
                                <td><?php echo $distrito ?></td>
                            </tr>
                            <tr>
                                <td><strong>Concelho</strong></td>
                                <td><?php echo $conselho ?></td>
                            </tr>
                            <tr>
                                <td><strong>Freguesia</strong></td>
                                <td><?php echo $freguesia ?></td>
                            </tr>
                            <tr>
                                <td><strong>Nome Representante</strong></td>
                                <td><?php echo $nome_rep ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email Representante</strong></td>
                                <td><?php echo $email_rep ?></td>
                            </tr>
                            <tr>
                                <td><strong>Morada</strong></td>
                                <td><?php echo $morada ?></td>
                            </tr>
                            <tr>
                                <td><strong>Website</strong></td>
                                <td><?php echo $website ?></td>
                            </tr>
                            <tr>
                                <td><strong>Descriçao da Instituicao</strong></td>
                                <td>
                                    <p  class="input-block-levelstyle"><?php echo $descricao ?></p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </div>
