<div class="wrap">
    <div class="icon32" id="icon-options-general"></div>
    <h2> <?php _e($this->page_title) ?> </h2>

    <?php if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == true) :  ?>
        <div class="updated fade"><p><?php _e('Plugin options updated.') ?></p></div>
     <?php endif; ?>

    <form action="options.php" method="post">

        <?php settings_fields($this->option_name); ?>

        <div class="ui-tabs">
            <ul class="ui-tabs-nav">

                <?php foreach ($this->sections as $section_slug => $section) { ?>
                    <li><a href="#<?php echo $section_slug ?>"><?php echo $section; ?></a></li>
                <?php } ?>

            </ul>

<?php do_settings_sections($_GET['page']); ?>

        </div>
        <p class="submit"><input name="Submit" type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" /></p>

    </form>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var sections = [];

            <?php 
            foreach ($this->sections as $section_slug => $section) :
                echo "sections['{$section}'] = '{$section_slug}';";
            endforeach; ?>

            var wrapped = $(".wrap h3").wrap("<div class='ui-tabs-panel'>");
            wrapped.each(function() {
                $(this).parent().append($(this).parent().nextUntil("div.ui-tabs-panel"));
            });
            $(".ui-tabs-panel").each(function(index) {
                $(this).attr("id", sections[$(this).children("h3").text()]);
                if (index > 0)
                    $(this).addClass("ui-tabs-hide");
            });
            $(".ui-tabs").tabs({
                fx: { opacity: "toggle", duration: "fast" }
            });
			
            $("input[type=text], textarea").each(function() {
                if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
                    $(this).css("color", "#999");
            });
			
            $("input[type=text], textarea").focus(function() {
                if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
                    $(this).val("");
                    $(this).css("color", "#000");
                }
            }).blur(function() {
                if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
                    $(this).val($(this).attr("placeholder"));
                    $(this).css("color", "#999");
                }
            });
			
            $(".wrap h3, .wrap table").show();
			
            // This will make the "warning" checkbox class really stand out when checked.
            // I use it here for the Reset checkbox.
            $(".warning").change(function() {
                if ($(this).is(":checked"))
                    $(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");
                else
                    $(this).parent().css("background", "none").css("color", "inherit").css("fontWeight", "normal");
            });
			
            // Browser compatibility
            if ($.browser.mozilla) 
                $("form").attr("autocomplete", "off");
        });
    </script>
</div>