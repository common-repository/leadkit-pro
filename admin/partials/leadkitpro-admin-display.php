<?php
   /**
    * Provide a admin area view for the plugin
    *
    * This file is used to markup the admin-facing aspects of the plugin.
    *
    * @link       jeeglo.com
    * @since      1.0.0
    *
    * @package    LeadKit PRO
    * @subpackage LeadKit PRO/admin/partials
    */
   ?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
    <?php
      if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly.
      }
    ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <head>
      <meta charset="<?php bloginfo( 'charset' ); ?>">
      <title>LeadKit PRO - Admin Interface</title>

      <!-- Mobile Specific Metas
         –––––––––––––––––––––––––––––––––––––––––––––––––– -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="shortcut icon" href="<?php echo plugins_url('images/favicon-icon.png', dirname(__FILE__));  ?>" />

     

   </head>
   <body id="lkpr-admin-body" <?php body_class(); ?>>
      <!-- Primary Page Layout
         –––––––––––––––––––––––––––––––––––––––––––––––––– -->
      <div class="lcd-main-container">
         <!-- HEADER -->
         <header class="block">
            <div class="lcd_logo">
               <a href="?page=leadkitpro"><img src="<?php echo plugins_url('images/logo.png', dirname(__FILE__));  ?>"></a>
            </div>
            <!-- <hr> -->
            
            <?php if(!$_GET['act'] && !$_GET['help']): ?>
            <div class="lcd_page_title">
               <div class="start_message">
               <h1>Welcome to LeadKit PRO</h1>
               <p>This plugin allows you to embed your Leadkit PRO campaigns directly to your website's pages without any hassle. </p>
               
                <div class="content-area">
                  <div class="dashboard-menu">
                    <ul>
                        <?php if(!empty($api_key)): ?>
                        <li><a href="?page=leadkitpro&act=1"><i class="fa fa-lock"></i>Connected</a></li>
                        <?php else: ?>
                        <li><a href="?page=leadkitpro&act=1"><i class="fa fa-unlock"></i>Connect</a></li>
                        <?php endif; ?>  
                        <li><a href="?page=leadkitpro&help=1"><i class="fa fa-support""></i>Help & Support</a></li>
                        <li style="display: none !important;" class="clearingCacheData"><a><i class="fa fa-spinner fa-spin"></i>Clearing Data... </a></li>
                        <li class="clearData"><a href="javascript:void(0);" class="clearAllCahceData"><i class="fa fa-database"></i>Clear Cache Data </a></li>
                        <li><a><i class="fa fa-code-fork"></i>Version <?php echo LKPR_PLUGIN_VERSION; ?> </a></li>
                    </ul>
                  </div>
                </div>
               </div>
            </div>
          <?php elseif ($_GET['help'] && $_GET['help'] == 1 && !$_GET['act']): ?>
            
            <div class="lcd_page_title">
               <div class="start_message">
                 
                 <div class="back-btn">
                  <a href="?page=leadkitpro" style="font-weight: bold;"><i class="fa fa-chevron-left""></i> BACK</a>
                 </div>
                 <h1>Need help with something?</h1>
                 <p>To create a ticket at our support desk send an email to <strong>"support@leadkit.pro"</strong>. All emails automatically become help tickets.<br> Please be patient...we answer every ticket! 
                 </p>
                 

               </div>
            </div>

          <?php elseif($_GET['act'] && $_GET['act'] == 1 && !$_GET['help']): ?>
            <div class="lcd_page_title">
               <div class="start_message">
                 
                 <div class="back-btn">
                  <a href="?page=leadkitpro" style="font-weight: bold;"><i class="fa fa-chevron-left""></i> BACK</a>
                 </div>
                 <h1>Connect your Account</h1>
                 <p>Enter your API key below to connect your LeadKit PRO account with your website. You may find your API key inside your LeadKit PRO account.</p>
                 <div class="activate-area">
                   <div style="width: 50%; float: left;">
                      <div class="content-area">
                        <div class="lcd-mt-20">
                          <div class="lcd-form">
                            <?php if($_GET['res'] && $_GET['res'] == 'inv'): ?>
                            <p style="color: red;">API Key is not valid, please use valid key.</p>
                            <?php endif; ?>
                            <form class="form-label form-css-label lcd-mt-20" method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
                              <input type="hidden" name="action" value="lcd_verfiy_api">
                              <fieldset>
                              <input id="lcd_license_key" name="_leadkitpro_api_key" type="text" value="<?php echo ($api_key ? $api_key : null) ?>" style="border: 1px solid #e0dede; padding-top: 35px;" required />
                              <label for="lcd_license_key" style="top: 10px; left: 10px; font-weight: bold;">API Key </label>
                              </fieldset>
                              <button class="lcd-mt-10 lcd-button lcd-button-small lcd-button-block lcd-form-submit-license" type="submit">Update</button>

                            </form>
                          </div>
                        </div>
                      </div>
                   </div>
                 </div>
               </div>
            </div>
          <?php else: ?>
            <p>Not Found!</p>
            <?php endif; ?>
            <!-- <hr> -->
         </header>
      </div>
      <?php
        wp_footer();
      ?>
   </body>
</html>