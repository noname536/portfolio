<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Jose Franco
 * Date: 18/04/2016
 * Time: 19:54
 *
 * Template footer para o website, todas as paginas fazem load deste html
 * Basicamente o footer fecha o <body>
 */
?>

        <!-- jQuery (necessary for Flat UI's JavaScript plugins) -->
        <script src="<?php echo base_url(); ?>bootstrap-flatui/js/vendor/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="<?php echo base_url(); ?>bootstrap-flatui/js/vendor/video.js"></script>
        <script src="<?php echo base_url(); ?>bootstrap-flatui/js/flat-ui.min.js"></script>
        <script src="<?php echo base_url(); ?>/js/styling.js"></script>
        <script src="<?php echo base_url(); ?>/js/form_validation.js"></script>
        <script src="<?php echo base_url(); ?>/js/login.js"></script>
        <script>
                applyBaseURL("<?php echo $base_url; ?>");
        </script>
    </body>
</html>