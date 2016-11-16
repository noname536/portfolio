
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
        <form>
        <div class="form-group has-feedback" id="div_label_email">
            <label for="label_email">E-mail:</label>
            <input type="text" class="form-control" id="label_email">
            <span id="label_email_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
            <span id="label_email_helpblock" class="help-block"></span>
        </div>
        <div class="form-group has-feedback" id="div_label_password">
            <label for="label_password">Password:</label>
            <input type="password" class="form-control" id="label_password">
            <span id="label_password_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
            <span id="label_password_helpblock" class="help-block"></span>
        </div>
        <div class="form-group has-feedback" id="div_label_password_confirm">
            <label for="label_password_confirm">Confirma&ccedil;&atilde;o da Password:</label>
            <input type="password" class="form-control" id="label_password_confirm">
            <span id="label_password_confirm_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
            <span id="label_password_confirm_helpblock" class="help-block"></span>
        </div>
        </form>

        <hr>
        <button id="submit_inst" class="btn btn-lg btn-success btn-embossed btn-wide pull-right submit_user_auth">
            Continuar como Institui&ccedil;&atilde;o
        </button>
        <button id="submit_voluntario" class="btn btn-lg btn-success btn-embossed btn-wide pull-right submit_user_auth">
            Continuar como Volunt&aacute;rio
        </button>
    </div>