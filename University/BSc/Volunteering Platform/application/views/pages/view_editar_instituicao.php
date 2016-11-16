
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Jose Franco
 * Date: 18/04/2016
 * Time: 19:54
 *
 * Pagina principal
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
                            <li class="active"><a href="<?php echo base_url(); ?>index.php/editar/instituicao/">Editar perfil</a></li>
                            <li><a href="<?php echo base_url(); ?>index.php/perfil/oportunidades/">Oportunidades</a></li>
                            <li><a href="<?php echo base_url(); ?>index.php/correspondencias" class="active">Voluntarios <span class="badge"><?php echo $numCorrs; ?></span></a></li>
                            <li><a href="<?php echo base_url(); ?>index.php/perfil/logout/">Sair</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>
        <div class="container">
            <h3>Edi&ccedil;&atilde;o de Institui&ccedil;&atilde;o</h3>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <img src="<?php echo base_url(); ?>/res/uploads/instituicao_default.png" class="img-responsive" >
                </div>

                <div class="col-md-6">
                    <div id="div_label_nome" class="form-group">
                        <label for="label_nome">Nome:</label>
                        <input type="text" class="form-control" id="label_nome" value= "<?php echo $nome ?>">
                        <span id="label_nome_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                        <span id="label_nome_helpblock" class="help-block"></span>
                    </div>

                    <div id="div_label_phone" class="form-group">
                        <label for="label_phone">Numero de Telefone:</label>
                        <input type="text" class="form-control" id="label_phone" value= "<?php echo $telefone ?>">
                        <span id="label_phone_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                        <span id="label_phone_helpblock" class="help-block"></span>
                    </div>

                    <div id="div_label_nomerep" class="form-group">
                        <label for="label_nomerep">Nome Representante:</label>
                        <input type="text" class="form-control" id="label_nomerep" value= "<?php echo $nomerep ?>">
                        <span id="label_nomerep_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                        <span id="label_nomerep_helpblock" class="help-block"></span>
                    </div>

                    <div id="div_label_emailrep" class="form-group">
                        <label for="label_emailrep"> Email Representante:</label>
                        <input type="text" class="form-control" id="label_emailrep" value= "<?php echo $emailrep ?>">
                        <span id="label_emailrep_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                        <span id="label_emailrep_helpblock" class="help-block"></span>
                    </div>

                    <div id="div_label_morada" class="form-group">
                        <label for="label_morada">Morada:</label>
                        <input type="text" class="form-control" id="label_morada" value= "<?php echo $morada ?>">
                        <span id="label_morada_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                        <span id="label_morada_helpblock" class="help-block"></span>
                    </div>

                    <div id="div_label_website" class="form-group">
                        <label for="label_website">Website:</label>
                        <input type="text" class="form-control" id="label_website" value= "<?php echo $website ?>">
                        <span id="label_website_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                        <span id="label_website_helpblock" class="help-block"></span>
                    </div>

                    <div id="div_label_desc" class="form-group">
                        <label for="label_desc">Descricao de Instituicao:</label>
                        <textarea class="form-control" rows="3" id="label_desc" style="resize: none;"><?php echo $descricao ?></textarea>
                        <span id="label_desc_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                        <span id="label_desc_helpblock" class="help-block"></span>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-inline">
                                <p>Localidade:</p>
                                <p>
                                    <select class="form-control select select-info" id="select_distrito">
                                        <optgroup label="Distrito">
                                            <option value="0">Distrito</option>
                                            <?php
                                            foreach ( $distritos as $distrito ) {
                                                echo "<option " . "value=" . $distrito['ID_DISTRITO'] .">" . $distrito['DISTRITO'] . "</option>";
                                            }
                                            ?>
                                        </optgroup>
                                    </select>
                                    <!--
									<script>
										$("#select_distrito").val(<?php echo $distritoV ?>);
									</script> -->
                                </p>
                                <p>
                                    <select class="form-control select select-info" id="select_concelho">
                                        <optgroup label="Concelho" id="optgroup_concelho">
                                            <option value="0">Concelho</option>
                                        </optgroup>
                                    </select>
									<!-- <script>

										$("#select_concelho").val(<?php echo $concelhoV ?>);
									</script> -->
                                </p>
                                <p>
                                    <select class="form-control select select-info" id="select_freguesia">
                                        <optgroup label="Freguesia" id="optgroup_freguesia">
                                            <option value="0">Freguesia</option>
                                        </optgroup>
                                    </select>
									<!-- <script>
										$("#select_freguesia").val(<?php echo $freguesiaV ?>);
										$("#select_freguesia").select2();
									</script> -->
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="row">
                <button id="submit_edit_instituicao" class="btn btn-lg btn-success btn-wide pull-right">
                    Salvar
                </button>
                <a href="<?php echo base_url(); ?>index.php/perfil/">
                    <button class="btn btn-lg btn-danger btn-wide pull-right">
                        Cancelar
                    </button>
                </a>
            </div>

            <hr>

        </div>
    </div>
