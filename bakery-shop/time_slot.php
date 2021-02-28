<form method="post" action="options.php">
            <?php
                settings_fields("crunchify_hello_world_config");
	do_settings_sections("crunchify-hello-world");
                submit_button();
            ?>
</form>


<?php
 
function crunchify_hello_world_settings() {
	add_settings_section("crunchify_hello_world_config", "", null, "crunchify-hello-world");
	add_settings_field("crunchify-hello-world-text", "This is sample Textbox", "crunchify_hello_world_options", "crunchify-hello-world", "crunchify_hello_world_config");
	register_setting("crunchify_hello_world_config", "crunchify-hello-world-text");
}
add_action("admin_init", "crunchify_hello_world_settings");
 
function crunchify_hello_world_options() {
?>
<div class="postbox" style="width: 65%; padding: 30px;">
	<input type="text" name="crunchify-hello-world-text"
		value="<?php
	echo stripslashes_deep(esc_attr(get_option('crunchify-hello-world-text'))); ?>" />
	Provide any text value here for testing<br />
</div>
<?php
}

?>