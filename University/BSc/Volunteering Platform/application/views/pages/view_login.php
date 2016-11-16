
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
                <input type="text" class="form-control" id="login_label_email">
                <span id="label_email_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                <span id="label_email_helpblock" class="help-block"></span>
            </div>
            <div class="form-group has-feedback" id="div_label_password">
                <label for="label_password">Password:</label>
                <input type="password" class="form-control" id="login_label_password">
                <span id="label_password_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
                <span id="label_password_helpblock" class="help-block"></span>
            </div>
            </form>
            <hr>
            <button id="submit_login" class="btn btn-lg btn-success btn-embossed btn-wide pull-right">Login</button>
    </div>