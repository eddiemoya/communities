<form action="<?php echo $action; ?>" method="POST">
<label for="loginId">Username</label>
<input type="text" name="loginId" />
<label for="logonPassword">Password</label>
<input type="password" name="logonPassword" />
<input type="hidden" name="sourceSiteId" value="<?php echo $auth->site_id(); ?>" />
<input type="hidden" name="service" value="<?php echo $auth->callback(); ?>" />
<input type="hidden" name="renew" value="true" />
<input type="submit" name="submit" value="Login" class="btn" />
</form>
