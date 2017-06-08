<?php 
/**
 * Your Inspiration Themes
 * 
 * In this files there is a collection of a functions useful for the core
 * of the framework.   
 * 
 * @package WordPress
 * @subpackage Your Inspiration Themes
 * @author Your Inspiration Themes Team <info@yithemes.com>
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
?>                         
    
            <?php
            /**
             * @see yit_footer
             */
            do_action( 'yit_footer') ?>
            
            <div class="wrapper-border"></div>
            
        </div>
        <!-- END WRAPPER -->
        <?php do_action( 'yit_after_wrapper' ) ?>        
        
    </div>
    <!-- END BG SHADOW -->
    
    <?php wp_footer() ?> 
    <script>
      (function($) {
          $('.logo-ivoryandart').on('click', function(event) {
              event.preventDefault();
              $.colorbox({html:"<img src='http://www.ivoryandart.com/popup1.png'width='500'height='70%'><a style='margin-left:20px; font-size;12px;' href='http://www.ivoryandart.com/education-center/all-about-mammoth-ivory/'>Read More about legal Mammoth Ivory here...</a>",innerWidth:"40%",innerHeight:"90%"});
          })
      })(jQuery);
    </script>
</body>
<!-- END BODY -->
</html>