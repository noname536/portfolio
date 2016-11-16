
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
                            <li class="active"><a href="<?php echo base_url(); ?>index.php/editar/voluntario/">Editar perfil</a></li>
                            <li><a href="<?php echo base_url(); ?>index.php/Correspondencias/">Oportunidades <span class="badge"><?php echo $numCorrs; ?></span></a></li>
                            <li><a href="<?php echo base_url(); ?>index.php/perfil/logout/">Sair</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>
        <div class="container">
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <img id="user_profile_pic" src="<?php echo $fotoV; ?>" class="img-responsive">
                    <p>Inserir foto de perfil:</p>
                    <form method="POST" id="form_file_foto" enctype="multipart/form-data">
                        <label id="label_browse_foto" class="btn btn-default btn-wide" for="browse_foto">
                            <input id="browse_foto" type="file" name="userfile" style="display: none">
                            Inserir foto
                        </label>
                        <label id="label_file_foto" class="btn btn-info btn-wide" for="file_foto">
                            <input id="file_foto" type="button" value="submit" style="display: none" />
                            Submeter foto
                        </label>
                    </form>
                    <script>
                        applyUploadedImage("<?php echo $fotoV; ?>");
                    </script>
                </div>

                <div class="col-md-6">
                    <div id="div_label_nome" class="form-group">
                        <label for="label_nome">Nome:</label>
                        <input type="text" class="form-control" id="label_nome" value= "<?php echo $nome ?>">
                        <span id="label_nome_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                        <span id="label_nome_helpblock" class="help-block"></span>
                    </div>

                    <div id="div_radio" class="form-group">
						<?php if( $genero == 'F'){ ?>
							<label class="radio-inline"><input name="gender" type="radio" value="M">Masculino</label>
							<label class="radio-inline"><input name="gender" type="radio" value="F" checked>Feminino</label>
						<?php }else { ?>
							<label class="radio-inline"><input name="gender" type="radio" value="M" checked>Masculino</label>
							<label class="radio-inline"><input name="gender" type="radio" value="F">Feminino</label>
						<?php } ?>
                        <span id="radio_helpblock" class="help-block"></span>
                    </div>

                    <div id="div_label_phone" class="form-group">
                        <label for="label_phone">Numero de Telefone:</label>
                        <input type="text" class="form-control" id="label_phone" value= "<?php echo $telefone ?>">
                        <span id="label_phone_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                        <span id="label_phone_helpblock" class="help-block"></span>
                    </div>
                    <div id="div_label_birth" class="form-group">
                        <label for="label_birth">Data de Nascimento:</label>
                        <input type="text" class="form-control" id="label_birth" value= "<?php echo $data_de_nascimento ?>">
                        <span id="label_birth_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                        <span id="label_birth_helpblock" class="help-block"></span>
                    </div>

                    <div class="row">
                        <div id="div_disponibilidade" class="col-lg-6">
                            <p>Disponibilidade:</p>
                            <select id="select_disponibilidade" class="form-control select select-info">
                                <optgroup label="Disponibilidade">
                                    <?php
                                    foreach ( $disponibilidade as $disp ) {
										if( $disp == $dispA ){
											echo "<option " . "value=" . $disp['ID_SEQD'] ." selected >" . ucfirst($disp['PERIODICIDADE']) . "</option>";
										}
                                        else{
                                            echo "<option " . "value=" . $disp['ID_SEQD'] .">" . ucfirst($disp['PERIODICIDADE']) . "</option>";
										}
									}
                                    ?>
                                </optgroup>
                            </select>
                            <script>
								$("#select_disponibilidade").val(<?php echo $disponibilidadeV ?>);
								$("#select_disponibilidade").select2();
							</script>
                        </div>
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
                <p>Interesses: </p>
                <div id="div_interesses" class="form-group">
                    <select id="select_interesses" multiple="multiple" class="form-control multiselect multiselect-default">
                        <optgroup label="Preferencias">
                            <?php
                            foreach ( $preferencias as $preferencia ) {
                                echo "<option " . "value=" . $preferencia['ID_SEQP'] .">" . $preferencia['PREF'] . "</option>";
                            }
                            ?>
                        </optgroup>
                    </select>
					<script>
						$("#select_interesses").val([<?php foreach( $interessesV as $i ) { echo $i . ", "; } ?>]);
					</script>
                </div>
            </div>
            <div id="div_grupos_atuacao" class="row">
                <p>Grupos de Atua&ccedil;&atilde;o: </p>
                <div class="form-group">
                    <select id="select_grupos_atuacao" multiple="multiple" class="form-control multiselect multiselect-default">
                        <optgroup label="Grupo_Atuacao">
                            <?php
                            foreach ( $grupo_atuacao as $grupo ) {
                                echo "<option " . "value=" . $grupo['ID_SEQGA'] .">" . $grupo['GRUPO'] . "</option>";
                            }
                            ?>
                        </optgroup>
                    </select>
					<script>
						$("#select_grupos_atuacao").val([<?php foreach( $grupoAtuacaoV as $i ) { echo $i . ", "; } ?>]);
					</script>
                </div>
            </div>
            <div id="div_habilitacoes"class="row">
                <p>Habilita&ccedil;&otilde;es Acad&eacute;micas:</p>
                <div class="form-group">
                    <select id="select_habilitacoes" multiple="multiple" class="form-control multiselect multiselect-default">
                        <optgroup label="Habilitacao_Academica">
                            <?php
                            foreach ( $habilitacao_academica as $grau ) {
                                echo "<option " . "value=" . $grau['ID_SEQHA'] .">" . $grau['GRAU'] . "</option>";
                            }
                            ?>
                        </optgroup>
                    </select>
					<script>
						$("#select_habilitacoes").val([<?php foreach( $habilitacaoAcademicaV as $i ) { echo $i . ", "; } ?>]);
					</script>
                </div>
            </div>
            <div class="row">
                <button id="submit_edit_voluntario" class="btn btn-lg btn-success btn-wide pull-right">
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
