
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
                        <li><a href="<?php echo base_url(); ?>index.php/perfil/oportunidades/">Oportunidades</a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/correspondencias" class="active">Voluntarios <span class="badge"><?php echo $numCorrs; ?></span></a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/perfil/logout/">Sair</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>
    </div>
    <div class="col-sm-9">
        <h3>Atualizar Oportunidade</h3>
        <hr>
        <div id="div_label_funcao" class="form-group">
            <label for="label_funcao">Fun&ccedil;&atilde;o:</label>
            <?php echo "<input type=\"text\" class=\"form-control\" id=\"label_funcao\" value=\"$funcao\" disabled>" ?>            
            <span id="label_funcao_icon" class="glyphicon glyphicon-remove form-control-feedback"></span>
            <span id="label_funcao_helpblock" class="help-block"></span>
        </div>
        <hr>
        <p>Localidade pretendida:</p>
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
        <hr>
        <p>Interesse pretendido: </p>
        <select id="select_interesse" class="form-control select select-primary">
            <optgroup label="Interesse">
                <?php
                    foreach ( $preferencias as $pref ) {
                        if($preferencia == $pref['ID_SEQP']){
                           echo "<option " . "value=" . $pref['ID_SEQP'] .">" . $pref['PREF'] . "</option>";
                        }
                    }
                    foreach ( $preferencias as $pref ) {
                        if($preferencia != $pref['ID_SEQP']){
                            echo "<option " . "value=" . $pref['ID_SEQP'] .">" . $pref['PREF'] . "</option>";
                        }
                    }
                ?>
            </optgroup>
        </select>
        <hr>
        <p>Grupo de atua&ccedil;&atilde;o pretendido: </p>
        <select id="select_grupo" class="form-control select select-primary">
            <optgroup label="Grupo de atua&ccedil;atilde;o">
                <?php
                    foreach ( $grupos_atuacoes as $grupo ) {
                        if($grupo_atuacao == $grupo['ID_SEQGA']){
                            echo " <option " . "value=" . $grupo['ID_SEQGA'] .">" . $grupo['GRUPO'] . "</option>";
                        }
                    }
                    foreach ( $grupos_atuacoes as $grupo ) {
                        if($grupo_atuacao != $grupo['ID_SEQGA']){
                            echo " <option " . "value=" . $grupo['ID_SEQGA'] .">" . $grupo['GRUPO'] . "</option>";
                        }
                    }
                ?>
            </optgroup>
        </select>
        <hr>
        <p>Habilita&ccedil;&atilde;o acad&eacute;mica pretendida:</p>
        <select id="select_habilitacoes" class="form-control select select-primary" >
            <optgroup label="Habilita&ccedil;&atilde;o acad&eacute;mica">
                <?php
                    foreach ( $habilitacoes_academicas as $grau ) {
                        if($habilitacao_academica == $grau['ID_SEQHA']){
                            echo "<option value= $grau[ID_SEQHA] > $grau[GRAU] </option>";
                        }
                    }
                    foreach ( $habilitacoes_academicas as $grau ) {
                        if($habilitacao_academica != $grau['ID_SEQHA']){
                            echo "<option value= $grau[ID_SEQHA] > $grau[GRAU] </option>";
                        }
                    }
                ?>
            </optgroup>
        </select>
        <hr>
        <p>Disponibilidade pretendida:</p>
        <select id="select_disponibilidade" class="form-control select select-primary">
            <optgroup label="Disponibilidade">
                <?php
                    foreach ( $disponibilidades as $disp ) {
                        if($disponibilidade == $disp['ID_SEQD']){
                            echo "<option value=" . $disp['ID_SEQD'] .">" . ucfirst($disp['PERIODICIDADE']) . "</option>";
                        }
                    }
                    foreach ( $disponibilidades as $disp ) {
                        if($disponibilidade != $disp['ID_SEQD']){
                            echo "<option value=" . $disp['ID_SEQD'] .">" . ucfirst($disp['PERIODICIDADE']) . "</option>";
                        }
                    }
                ?>
            </optgroup>
        </select>
        <hr>
        <div class="row">
            <button id="submit_edit_oportunidade" class="btn btn-lg btn-success btn-wide pull-right">
                    Salvar
            </button>
            <a href="<?php echo base_url(); ?>index.php/perfil/oportunidades/">
                <button class="btn btn-lg btn-danger btn-wide pull-right">
                    Cancelar
                </button>
            </a>
        </div>
    </div>
</div>
