
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//dps poe se uns butoes consoante as correspondencias?


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
                        <li class="active"><a href="<?php echo base_url(); ?>index.php/correspondencias">Oportunidades</a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/perfil/logout/">Sair</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>
    </div>
    <div class="col-sm-9">
        <h3>Oportunidades></h3>
        <hr>
        <table class="table table-hover">
            <tbody>
                <?php foreach($oportunities as $op) { ?>
                <tr class="active">
                    <td><strong><?php echo $op['nome']; ?></strong></td>
                    <td>
                        
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
