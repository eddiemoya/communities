<article class="content-container register span12">
    <section class="content-body clearfix">
        <h6 class="content-headline">Register</h6>

            <div id="sso-error"></div>
     
        <form class="form_register" id="registration" method="post" action="" shc:gizmo="transFormer">
            <ul class="form-fields">
                <li>
                    <dl class="clearfix">
                        <dt class="span3"><label for="loginId">Email:</label></dt>
                        <dd class="span9"><input type="text" name="loginId" autocomplete="off" class="input_text" id="loginId" shc:gizmo:form="{required:true, trim:true, pattern: /^.+@.+?\.[a-zA-Z]{2,}$/, message: 'Please enter a valid email address'}" value="<?php echo $email; ?>"/></dd>
                    </dl>
                </li>

                <li>
                    <dl class="clearfix">
                        <dt class="span3"><label for="login_confirm-email">Confirm Email:</label></dt>
                        <dd class="span9">
                            <input type="text" name="login_confirm-email" autocomplete="off" class="input_text" id="login_confirm-email" shc:gizmo:form="{required:true, trim:true, custom: function(self) {if (self.value.toLowerCase() != ($('#loginId').attr('value')).toLowerCase()) return false; else return true;}, message: 'Your email does not match. Please check and try again.'}" value="<?php echo $email;?>" />
                        </dd>
                    </dl>
                </li>

                <li>
                    <dl class="clearfix">
                        <dt class="span3"><label for="logonPassword">Password:</label></dt>
                        <dd class="span9">
                            <input
                            type="password"
                            name="logonPassword"
                            autocomplete="off"
                            class="input_text input_password"
                            id="logonPassword"
                            shc:gizmo:form="{required:true, pattern: /^\w*(?=\w{8,})(?=\w*\d)(?=\w*[a-zA-Z])(?!\w*_)\w*$/, message: 'Please enter a valid password.'}"
                            shc:gizmo="tooltip"
                            shc:gizmo:options="
                            {
                                tooltip: {
                                    displayData: {
                                        element: 'passInfo'
                                    },
                                    events: {
                                        click: {
                                            active: false
                                        },
                                        blur: {
                                            active: true
                                        },
                                        focus: {
                                            active: true
                                        }
                                    },
                                    arrowPosition: 'left'
                                }
                            }"
                            />
                        </dd>
                    </dl>
                </li>

                <li>
                    <dl class="clearfix">
                        <dt class="span3"><label for="zipcode">ZIP Code:</label></dt>
                        <dd class="span9"><input type="text" name="zipcode" autocomplete="off" class="input_text input_password" id="zipcode" shc:gizmo:form="{required:true, trim:true, pattern: /(^\d{5})(-\d{4})?$/, message: 'Please enter a valid ZIP code'}" value="<?php echo $zipcode;?>" /></dd>
                    </dl>
                </li>

                <li class="clearfix">
                    <dl>
                        <dd class="span3">&nbsp;</dd>
                        <dd class="span9">
                            <p>
                                <input type="checkbox" name="offers" id="offers" value="True" class="input_checkbox" checked="checked"/> I would like to receive offers, updates and sale alerts from Sears
                            </p>
                        </dd>
                    </dl>
                </li>

                <li class="clearfix">
                    <dl>
                        <dd class="span3">&nbsp;</dd>
                        <dd class="span9">
                            <button type="submit" class="<?php echo theme_option("brand"); ?>_button">Register</button>
                        </dd>
                    </dl>
                </li>

                <li class="clearfix">
                    <dl>
                        <dd class="span3">&nbsp;</dd>
                        <dd class="span9">
                            <p>
                                By clicking &quot;Register&quot;, I agree to the <a href="<?php echo (theme_option("brand") == "sears")? "http://www.sears.com/shc/s/nb_10153_12605_NB_CStermsofservice":"http://www.kmart.com/csterms/nb-100000000000005?adCell=WF"; ?>" target="_blank" title="Terms of Use">Terms of Use</a> and <a href="<?php echo (theme_option("brand") == "sears")? "http://www.sears.com/shc/s/nb_10153_12605_NB_CSprivacy":"http://www.kmart.com/csprivacy/nb-100000000000006?adCell=WF"; ?>" target="_blank" title="Privacy Policy">Privacy Policy</a>.
                            </p>
                        </dd>
                    </dl>
                </li>
            </ul>
			<input type="hidden" name="service" value="<?php //echo $opts->url_append_qs("origin={$origin}&ssoregister", urldecode($origin));?>" />
			<input type="hidden" name="sourceSiteid" value="<?php //echo $opts->sso_site_id;?>" />
			
        </form>

        <ul>
            <li class="clearfix">
                <dl>
                    <dd class="span3">&nbsp;</dd>
                    <dd class="span9">
                        <p class="bold">
                            Existing Customer? <a href="/login/" title="Sign In" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}}}">Sign In</a>
                        </p>
                    </dd>
                </dl>
            </li>
        </ul>
        <div id="passInfo" class="info hide">
            <p class="bold">Your password must have:</p>
            <ul>
                <li>6 or more characters total</li>
                <li>At least one letter</li>
                <li>At least one number</li>
                <li>No space</li>
                <li>No special characters such as ! or ?</li>
            </ul>
            <p>All passwords are cAsE sEnSiTiVe.</p>
        </div>

        <section id="login-open-id" class="open-id" shc:gizmo="openID">
            <span class="or">OR</span>
            <p>use your account from</p>
            <ul class="open-id-services clearfix">
                <li class="open-id_service open-id_facebook"><a href="#" shc:openID="facebook">Facebook</a></li>
                <li class="open-id_service open-id_yahoo"><a href="#" shc:openID="yahoo">Yahoo!</a></li>
                <li class="open-id_service open-id_google"><a href="#" shc:openID="google">Google</a></li>
                <!-- <li class="open-id_service open-id_twitter"><a href="#" shc:openID="twitter">Twitter</a></li> -->
            </ul>
        </section>

    </section>
</article>