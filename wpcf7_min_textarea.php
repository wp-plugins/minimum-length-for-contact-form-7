<?php
/* Tag generator */

add_action( 'admin_init', 'wpcf7_add_tag_generator_min_textarea', 20 );

function wpcf7_add_tag_generator_min_textarea() {
	if ( ! function_exists( 'wpcf7_add_tag_generator' ) )
		return;

	wpcf7_add_tag_generator( 'textarea', __( 'Text area', 'contact-form-7' ),
		'wpcf7-tg-pane-min-textarea', 'wpcf7_tg_pane_min_textarea' );
}

function wpcf7_tg_pane_min_textarea( &$contact_form ) {
?>
<div id="wpcf7-tg-pane-textarea" class="hidden">
<form action="">
<table>
<tr><td><input type="checkbox" name="required" />&nbsp;<?php echo esc_html( __( 'Required field?', 'contact-form-7' ) ); ?></td></tr>
<tr><td><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?><br /><input type="text" name="name" class="tg-name oneline" /></td><td></td></tr>
</table>

<table>
<tr>
<td><code>id</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
<input type="text" name="id" class="idvalue oneline option" /></td>

<td><code>class</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
<input type="text" name="class" class="classvalue oneline option" /></td>
</tr>

<tr>
<td><code>cols</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
<input type="number" name="cols" class="numeric oneline option" min="1" /></td>

<td><code>rows</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
<input type="number" name="rows" class="numeric oneline option" min="1" /></td>
</tr>

<tr>
<td><code>maxlength</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
<input type="number" name="maxlength" class="numeric oneline option" min="1" /></td>
<td><code>minlength</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
<input type="number" name="minlength" class="numeric oneline option" /></td>
</tr>

<tr>
<td><?php echo esc_html( __( 'Default value', 'contact-form-7' ) ); ?> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br /><input type="text" name="values" class="oneline" /></td>

<td>
<br /><input type="checkbox" name="placeholder" class="option" />&nbsp;<?php echo esc_html( __( 'Use this text as placeholder?', 'contact-form-7' ) ); ?>
</td>
</tr>
</table>

<div class="tg-tag"><?php echo esc_html( __( "Copy this code and paste it into the form left.", 'contact-form-7' ) ); ?><br /><input type="text" name="textarea" class="tag wp-ui-text-highlight code" readonly="readonly" onfocus="this.select()" /></div>

<div class="tg-mail-tag"><?php echo esc_html( __( "And, put this code into the Mail fields below.", 'contact-form-7' ) ); ?><br /><input type="text" class="mail-tag wp-ui-text-highlight code" readonly="readonly" onfocus="this.select()" /></div>
</form>
</div>
<?php
}

?>