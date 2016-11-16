
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
        <hr>
        <div class="row">
            <div id="div_label_nome" class="form-group">
                <label for="label_nome">Nome:</label>
                <input type="text" class="form-control" id="label_nome">
                <span id="label_nome_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                <span id="label_nome_helpblock" class="help-block"></span>
            </div>

            <div id="div_label_phone" class="form-group">
                <label for="label_phone">Numero de Telefone:</label>
                <input type="text" class="form-control" id="label_phone">
                <span id="label_phone_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                <span id="label_phone_helpblock" class="help-block"></span>
            </div>
            <div id="div_label_desc" class="form-group">
                <label for="label_desc">Descri&ccedil;&atilde;o:</label>
                <textarea class="form-control" rows="3" id="label_desc"></textarea>
                <span id="label_desc_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                <span id="label_desc_helpblock" class="help-block"></span>
            </div>

            <p>Localidade:</p>
            <div class="form-inline">
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
                <select class="form-control select select-info" id="select_concelho">
                    <optgroup label="Concelho" id="optgroup_concelho">
                        <option value="0">Concelho</option>
                    </optgroup>
                </select>
                <select class="form-control select select-info" id="select_freguesia">
                    <optgroup label="Freguesia" id="optgroup_freguesia">
                        <option value="0">Freguesia</option>
                    </optgroup>
                </select>
            </div>

            <div id="div_label_morada" class="form-group">
                <label for="label_morada">Morada:</label>
                <input type="text" class="form-control" id="label_morada">
                <span id="label_morada_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                <span id="label_morada_helpblock" class="help-block"></span>
            </div>

            <div id="div_label_website" class="form-group">
                <label for="label_website">Website:</label>
                <input type="text" class="form-control" id="label_website">
                <span id="label_website_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                <span id="label_website_helpblock" class="help-block"></span>
            </div>

            <div id="div_label_nomerep" class="form-group">
                <label for="label_nomerep">Nome do Representante:</label>
                <input type="text" class="form-control" id="label_nomerep">
                <span id="label_nomerep_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                <span id="label_nomerep_helpblock" class="help-block"></span>
            </div>

            <div id="div_label_emailrep" class="form-group">
                <label for="label_emailrep">E-mail do Representante:</label>
                <input type="text" class="form-control" id="label_emailrep">
                <span id="label_emailrep_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                <span id="label_emailrep_helpblock" class="help-block"></span>
            </div>

            <hr>

        </div>

        <div class="row">
            <button id="submit_registo_instituicao" class="btn btn-hg btn-success btn-embossed btn-wide pull-right">
                Concluir
            </button>
        </div>
    </div>