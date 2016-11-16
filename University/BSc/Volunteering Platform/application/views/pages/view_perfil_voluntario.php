
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
                            <li><a href="<?php echo base_url(); ?>index.php/editar/voluntario/">Editar perfil</a></li>
                            <li><a href="<?php echo base_url(); ?>index.php/Correspondencias/">Oportunidades <span class="badge"><?php echo $numCorrs; ?></span></a></li>
                            <li><a href="<?php echo base_url(); ?>index.php/perfil/logout/">Sair</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>
        <div class="col-sm-9">
            <div class="container">
                <h3>
                <?php echo $nome ?>
                </h3>
                <div class="row">
                    <div class="col-md-2">
                                           
                    <img src="<?php echo $foto; ?>" class="img-responsive" >
                    </div>
                    <div class="col-lg-8">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <td><strong>Nome</strong></td>
                                <td><?php echo $nome ?></td>
                            </tr>
                            <tr>
                                <td><strong>Data de Nascimento</strong></td>
                                <td><?php echo $nascimento ?></td>
                            </tr>
                            <tr>
                                <td><strong>Genero</strong></td>
                                <td><?php echo $genero ?></td>
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
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <p>Interesses: </p>
                    <div class="form-group">
                        <select multiple="multiple" class="form-control multiselect multiselect-default" disabled>
                        <?php                                                 
                            foreach ($arrPref as $pref) {
                                echo "<option selected>$pref</option>";
                            }                          
                        ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <p>Grupos de Atua&ccedil;&atilde;o: </p>
                    <div class="form-group">
                        <select multiple="multiple" class="form-control multiselect multiselect-default" disabled>                   
                            <?php                                                 
                            // <option value="3" selected>Grupo 4</option>
                            foreach ($arrGrupo as $grupo) {
                                echo "<option selected>$grupo</option>";
                            }                          
                        ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <p>Habilita&ccedil;&atilde;o Acad&eacute;mica:</p>
                    <div class="form-group">
                    <select multiple="multiple" class="form-control multiselect multiselect-default" disabled>
                    <?php 
                        foreach ($arrHab as $hab) {
                            echo "echo <option selected>$hab</option>";
                        }
                    ?> 
                    </select>
                    </div>                   
                </div>
                <div class="row">
                    <p>Disponibilidade:</p>
                    <div class="form-group">
                        <select multiple="multiple" class="form-control multiselect multiselect-default" disabled>
                        <?php 
                            echo "echo <option selected>".ucfirst($disponibilidade)."</option>";
                        ?>
                        </select>             
                    </div>
                </div>
            </div>
        </div>
    </div>
