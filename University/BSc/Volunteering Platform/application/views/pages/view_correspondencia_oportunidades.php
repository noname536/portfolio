
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
                        <li><a href="<?php echo base_url(); ?>index.php/editar/voluntario">Editar perfil</a></li>
                        <li class="active"><a href="<?php echo base_url(); ?>index.php/Correspondencias/">Oportunidades <span class="badge"><?php echo $numCorrs; ?></span></a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/perfil/logout/">Sair</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>
    </div>
    <div class="col-sm-9">
        <div class="container">
            <h3>Lista de oportunidades correspondidas</h3>
            <br>
       	    <div class="row">
	        	<div class="col-md-12">
	            	<form action="" class="search-form">
	                	<div class="form-group has-feedback">
	            			<label for="search" class="sr-only">Search</label>
	            			<input type="text" class="form-control" name="search" id="search" 
	            					placeholder="Procurar instituição ou oportunidades">
	              			<span class="glyphicon glyphicon-search form-control-feedback"></span>
	            		</div>
	            	</form>
	        	</div>
    		</div>
            <hr>
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <td><strong>E-mail</strong></td>
                            <td><strong>Oportunidade</strong></td>
                            <td><strong>Grau de Correspondencia</strong></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach( $oportunities as $oportunidade ) { ?>

                        <tr>
                            
                            <td><?php echo $oportunidade["EMAIL"]; ?></td>
    
                            <td><?php echo $oportunidade["OPORTUNIDADE"] ?></td>

                            <td>

                                <?php
                                // localidade check?
                                if ( $oportunidade["ID_DISTRITO"] && $oportunidade["ID_CONCELHO"] && $oportunidade["ID_FREGUESIA"] ) 
                                {
                                    echo "<span class=\"glyphicon glyphicon-home\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Localidade\"></span>";
                                }
                                else if ( $oportunidade["ID_DISTRITO"] && $oportunidade["ID_CONCELHO"] )
                                {
                                    echo "<span class=\"glyphicon glyphicon-home\" style=\"color: grey;\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Distrito/Concelho\"></span>";
                                }
                                else {
                                    echo "<span class=\"glyphicon glyphicon-home\" style=\"color: darkred;\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Distrito\"></span>";
                                }
                                                                               
                                // disponibilidade check?
                                if ( $oportunidade["ID_SEQD"] ) 
                                {
                                    echo "<span class=\"glyphicon fui-calendar\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Disponibilidade\"></span>";
                                }
                                
                                // habilitacao academica check?
                                if ( $oportunidade["ID_SEQHA"] ) 
                                {
                                    echo "<span class=\"glyphicon glyphicon-book\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Habilitacao Academica\"></span>";
                                }
                                                                               
                                // grupo atuacao check?
                                if ( $oportunidade["ID_SEQGA"] ) 
                                {
                                    echo "<span class=\"glyphicon glyphicon-user\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Grupo de Atuacao\"></span>";
                                }
                                
                                // interesse check?
                                if ( $oportunidade["ID_SEQP"] ) 
                                {
                                    echo "<span class=\"glyphicon glyphicon-star\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Interesse\"></span>";
                                }
                                ?>
                                 <a href="<?php echo base_url(); ?>index.php/Correspondencias/oportunidade/<?php echo $oportunidade['url']; ?>">
                                <button id="submit_criacao_oportunidade" class="btn btn-sm btn-info btn-embossed pull-right">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                            
                                <div class="progress">
                                    <div class="progress-bar" style="width: <?php echo $oportunidade["COUNT"]; ?>%;"></div>
                                </div>
                                </a>

                            </td>
                            
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            <hr>
        </div>
    </div>
</div>   
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
