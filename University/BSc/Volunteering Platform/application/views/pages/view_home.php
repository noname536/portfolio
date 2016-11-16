<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

    <div id="big_jumbotron" class="carousel slide" data-ride="carousel">

        <div class="carousel-inner" role="listbox">
            <div class="item active">
                <div class="background-slide-1"></div>
                <div class="carousel-caption">

                    <h2>Participas em atividades de voluntariado?</h2>
                    <h6>Este site ajuda-te a encontrar a institui&ccedil;&atilde;o perfeita para ti!</h6>
                    <h3>Regista-te j&aacute;!</h3>
                    <a href="<?php echo base_url(); ?>index.php/registo/">
                        <button class="btn btn-lg btn-primary btn-embossed btn-wide">
                            Registrar <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                    </a>
                    <a href="<?php echo base_url(); ?>index.php/login/">
                        <button class="btn btn-lg btn-default btn-embossed btn-wide">
                            Entrar <span class="glyphicon glyphicon-user"></span>
                        </button>
                    </a>

                </div>
            </div>
            <div class="item">
                <div class="background-slide-2"></div>
                <div class="carousel-caption">
                    <h2>Institui&ccedil;&otilde;es tamb&eacute;m s&atilde;o bem vindas!</h2>
                    <h6>Regista a sua institui&ccedil;&atilde;o de modo encontrar possiveis volunt&aacute;rios!</h6>
                    <h3>Registem-se j&aacute;!</h3>
                    <a href="<?php echo base_url(); ?>index.php/registo/">
                        <button class="btn btn-lg btn-primary btn-embossed btn-wide">
                            Registrar <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                    </a>
                    <a href="<?php echo base_url(); ?>index.php/login/">
                        <button class="btn btn-lg btn-default btn-embossed btn-wide">
                            Entrar <span class="glyphicon glyphicon-user"></span>
                        </button>
                    </a>
                </div>
            </div>
            <!--<div class="item">
                <div class="background-slide-3"></div>
                <div class="carousel-caption">
                </div>
            </div>-->
        </div>
        <a class="left carousel-control" href="#big_jumbotron" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#big_jumbotron" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <hr>
