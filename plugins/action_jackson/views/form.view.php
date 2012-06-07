<div class="wrap clearfix">
	<div class="icon32" id="icon-options-general"><br></div>
	<h2>FitStudio Header/Footer Options</h2>
</div>

<form action="options.php" method="post">
	<table class="form-table">
<?php
	settings_fields('fitStudioOptions');
	do_settings_sections('manage-fitstudio-hf');
?>
	</table>
	<p class="submit">
		<input type="submit" class="button-primary" value="Submit" />
	</p>
</form>