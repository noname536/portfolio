
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


    <div class="container">
        <h1>Registo de Volunt&aacute;rio</h1>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <img id="user_profile_pic" src="<?php echo base_url(); ?>/res/uploads/profile_default.jpg" class="img-responsive">
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
            </div>

            <div class="col-md-8">
                <div id="div_label_nome" class="form-group">
                    <label for="label_nome">Nome:</label>
                    <input type="text" class="form-control" id="label_nome">
                    <span id="label_nome_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                    <span id="label_nome_helpblock" class="help-block"></span>
                </div>

                <div id="div_radio" class="form-group">
                    <label class="radio-inline"><input name="gender" type="radio" value="M">Masculino</label>
                    <label class="radio-inline"><input name="gender" type="radio" value="F">Feminino</label>
                    <span id="radio_helpblock" class="help-block"></span>
                </div>

                <div id="div_label_phone" class="form-group">
                    <label for="label_phone">Numero de Telefone:</label>
                    <input type="text" class="form-control" id="label_phone">
                    <span id="label_phone_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                    <span id="label_phone_helpblock" class="help-block"></span>
                </div>
                <div id="div_label_birth" class="form-group">
                    <label for="label_birth">Data de Nascimento:</label>
                    <input type="text" class="form-control" id="label_birth">
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
                                    echo "<option " . "value=" . $disp['ID_SEQD'] .">" . ucfirst($disp['PERIODICIDADE']) . "</option>";
                                }
                            ?>
                            </optgroup>
                        </select>
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
                            </p>
                            <p>
                                <select class="form-control select select-info" id="select_concelho">
                                    <optgroup label="Concelho" id="optgroup_concelho">
                                        <option value="0">Concelho</option>
                                    </optgroup>
                                </select>
                            </p>
                            <p>
                                <select class="form-control select select-info" id="select_freguesia">
                                    <optgroup label="Freguesia" id="optgroup_freguesia">
                                        <option value="0">Freguesia</option>
                                    </optgroup>
                                </select>
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
            </div>
        </div>
        <div class="row">
            <button id="submit_registo_voluntario" class="btn btn-hg btn-success btn-embossed btn-wide pull-right">
                Concluir
            </button>
        </div>

        <hr>

        

    </div>