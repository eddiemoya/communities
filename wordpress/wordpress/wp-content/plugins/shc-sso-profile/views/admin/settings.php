<div class="wrap clearfix">
    <div class="icon32" id="icon-options-general"><br></div>
    <h2><?php echo __('Sears Holdings SSO and Profile Settings'); ?></h2>
</div>
<form action="options.php" method="post" id="<?php echo SHCSSO_OPTION_PREFIX . 'settings'; ?>">
<?php settings_fields(SHCSSO_OPTION_PREFIX . 'settings'); ?>
<?php do_settings_sections('shcsso-settings'); ?>
<?php submit_button(); ?>
</form>
