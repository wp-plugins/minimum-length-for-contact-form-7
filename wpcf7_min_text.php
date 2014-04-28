<?php
/* Tag generator */

add_action( 'admin_init', 'wpcf7_add_tag_generator_min_text', 15 );

function wpcf7_add_tag_generator_min_text() {
	if ( ! function_exists( 'wpcf7_add_tag_generator' ) )
		return;

	wpcf7_add_tag_generator( 'text', __( 'Text field', 'contact-form-7' ),
		'wpcf7-tg-pane-min-text', 'wpcf7_tg_pane_min_text' );

	wpcf7_add_tag_generator( 'email', __( 'Email', 'contact-form-7' ),
		'wpcf7-tg-pane-min-email', 'wpcf7_tg_pane_min_email' );

	wpcf7_add_tag_generator( 'url', __( 'URL', 'contact-form-7' ),
		'wpcf7-tg-pane-min-url', 'wpcf7_tg_pane_min_url' );

	wpcf7_add_tag_generator( 'tel', __( 'Telephone number', 'contact-form-7' ),
		'wpcf7-tg-pane-min-tel', 'wpcf7_tg_pane_min_tel' );
}

function wpcf7_tg_pane_min_text( &$contact_form ) {
	wpcf7_tg_pane_min_text_and_relatives( 'text' );
}

function wpcf7_tg_pane_min_email( &$contact_form ) {
	wpcf7_tg_pane_min_text_and_relatives( 'email' );
}

function wpcf7_tg_pane_min_url( &$contact_form ) {
	wpcf7_tg_pane_min_text_and_relatives( 'url' );
}

function wpcf7_tg_pane_min_tel( &$contact_form ) {
	wpcf7_tg_pane_min_text_and_relatives( 'tel' );
}

function wpcf7_tg_pane_min_text_and_relatives( $type = 'text' ) {
	if ( ! in_array( $type, array( 'email', 'url', 'tel' ) ) )
		$type = 'text';

?>
<div id="wpcf7-tg-pane-<?php echo $type; ?>" class="hidden">
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
<td><code>size</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
<input type="number" name="size" class="numeric oneline option" min="1" /></td>

<td><code>maxlength</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
<input type="number" name="maxlength" class="numeric oneline option" min="1" /></td>
</tr>
<td><code>minlength</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
<input type="number" name="minlength" class="numeric oneline option" /></td>
<tr>
<td></td>

</tr>

<?php if ( in_array( $type, array( 'text', 'email', 'url' ) ) ) : ?>
<tr>
<td colspan="2"><?php echo esc_html( __( 'Akismet', 'contact-form-7' ) ); ?> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
<?php if ( 'text' == $type ) : ?>
<input type="checkbox" name="akismet:author" class="option" />&nbsp;<?php echo esc_html( __( "This field requires author's name", 'contact-form-7' ) ); ?><br />
<?php elseif ( 'email' == $type ) : ?>
<input type="checkbox" name="akismet:author_email" class="option" />&nbsp;<?php echo esc_html( __( "This field requires author's email address", 'contact-form-7' ) ); ?>
<?php elseif ( 'url' == $type ) : ?>
<input type="checkbox" name="akismet:author_url" class="option" />&nbsp;<?php echo esc_html( __( "This field requires author's URL", 'contact-form-7' ) ); ?>
<?php endif; ?>
</td>
</tr>
<?php endif; ?>

<tr>
<td><?php echo esc_html( __( 'Default value', 'contact-form-7' ) ); ?> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br /><input type="text" name="values" class="oneline" /></td>

<td>
<br /><input type="checkbox" name="placeholder" class="option" />&nbsp;<?php echo esc_html( __( 'Use this text as placeholder?', 'contact-form-7' ) ); ?>
</td>
</tr>
</table>

<div class="tg-tag"><?php echo esc_html( __( "Copy this code and paste it into the form left.", 'contact-form-7' ) ); ?><br /><input type="text" name="<?php echo $type; ?>" class="tag wp-ui-text-highlight code" readonly="readonly" onfocus="this.select()" /></div>

<div class="tg-mail-tag"><?php echo esc_html( __( "And, put this code into the Mail fields below.", 'contact-form-7' ) ); ?><br /><input type="text" class="mail-tag wp-ui-text-highlight code" readonly="readonly" onfocus="this.select()" /></div>
</form>
</div>
<?php
}
?>