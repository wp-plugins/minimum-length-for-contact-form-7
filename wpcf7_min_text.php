<?php

/*
Plugin Name: Contact Form 7 - Minlength Text Extension
Plugin URI: http://wordpress.org/plugins/minimum-length-for-contact-form-7/
Description: Add (required) minimum length to textfields in Contact Form 7.
Version: 1.0
Author: Tussendoor internet & marketing
Author URI: http://tussendoor.nl
*/

function wpcf7_min_text_init()
{
	if(function_exists('wpcf7_add_shortcode'))
	{
		wpcf7_add_shortcode( 'text', 'wpcf7_min_text_shortcode_handler', true );
		wpcf7_add_shortcode( 'text*', 'wpcf7_min_text_shortcode_handler', true );
	}	
	add_filter( 'wpcf7_validate_text', 'wpcf7_min_text_validation_filter', 10, 2 );
	add_filter( 'wpcf7_validate_text*', 'wpcf7_min_text_validation_filter', 10, 2 );
}
add_action('init', 'wpcf7_min_text_init');

function wpcf7_min_text_shortcode_handler( $tag ) 
{
	if ( ! is_array( $tag ) )
		return '';

	$type = $tag['type'];
	$name = $tag['name'];
	$options = (array) $tag['options'];
	$values = (array) $tag['values'];

	if ( empty( $name ) )
		return '';

	$atts = '';
	$id_att = '';
	$class_att = '';
	$size_att = '';
	$maxlength_att = '';
	$minlength_att = '';
	$tabindex_att = '';
	$title_att = '';

	$class_att .= ' wpcf7-text';

	if ( 'email' == $type || 'email*' == $type )
		$class_att .= ' wpcf7-validates-as-email';

	if ( 'text*' == $type || 'email*' == $type )
		$class_att .= ' wpcf7-validates-as-required';
	
	foreach ( $options as $option ) 
	{
		if ( preg_match( '%^id:([-0-9a-zA-Z_]+)$%', $option, $matches ) ) 
		{
			$id_att = $matches[1];
		} 
		elseif ( preg_match( '%^class:([-0-9a-zA-Z_]+)$%', $option, $matches ) ) 
		{
			$class_att .= ' ' . $matches[1];

		} 
		elseif ( preg_match( '%^minlength:([0-9]*)$%', $option, $matches ) ) 
		{
			$minlength_att = (int) $matches[1];
		}
		elseif ( preg_match( '%^([0-9]*)[/x]([0-9]*)$%', $option, $matches ) ) 
		{
			$size_att = (int) $matches[1];
			$maxlength_att = (int) $matches[2];
		}
		elseif ( preg_match( '%^tabindex:(\d+)$%', $option, $matches ) ) 
		{
			$tabindex_att = (int) $matches[1];
		}
	}
	
	$value = (string) reset( $values );

	if ( wpcf7_script_is() && preg_grep( '%^watermark$%', $options ) ) 
	{
		$class_att .= ' wpcf7-use-title-as-watermark';
		$title_att .= sprintf( ' %s', $value );
		$value = '';
	}

	if ( wpcf7_is_posted() && isset( $_POST[$name] ) )
		$value = stripslashes_deep( $_POST[$name] );

	if ( $id_att )
		$atts .= ' id="' . trim( $id_att ) . '"';

	if ( $class_att )
		$atts .= ' class="' . trim( $class_att ) . '"';

	if ( $size_att )
		$atts .= ' size="' . $size_att . '"';
	else
		$atts .= ' size="40"'; // default size

	if ( $maxlength_att )
		$atts .= ' maxlength="' . $maxlength_att . '"';

	if ( $minlength_att )
		$atts .= ' minlength="' . $minlength_att . '"';

	if ( '' !== $tabindex_att )
		$atts .= sprintf( ' tabindex="%d"', $tabindex_att );

	if ( $title_att )
		$atts .= sprintf( ' title="%s"', trim( esc_attr( $title_att ) ) );

	$html = '<input type="text" name="' . $name . '" value="' . esc_attr( $value ) . '"' . $atts . ' />';

	$validation_error = wpcf7_get_validation_error( $name );

	$html = '<span class="wpcf7-form-control-wrap ' . $name . '">' . $html . $validation_error . '</span>';

	return $html;
}


/* Validation filter */
function wpcf7_min_text_validation_filter( $result, $tag ) 
{
	global $wpcf7_contact_form;
	
	$type = $tag['type'];
	$name = $tag['name'];
	
	$_POST[$name] = trim( strtr( (string) $_POST[$name], "\n", " " ) );
	
	if ( 'text' == $type || 'text*' == $type ) 
	{
		foreach($tag['options'] as $option)
		{
			if(strpos($option, 'minlength:') === 0)
			{
				$minlength = array_pop(explode(':', $option));
				if(strlen($_POST[$name]) < $minlength)
				{
					$result['valid'] = false;
					$result['reason'][$name] = $wpcf7_contact_form->message( 'invalid_length' );
				}
				break;
			}
		}
	}
	
	return $result;
}

add_filter( 'wpcf7_messages', 'wpcf7_min_text_messages' );

function wpcf7_min_text_messages( $messages ) 
{
	return array_merge( $messages, 
			array( 
				'invalid_length' => 
					array(
						'description'	=> __( 'The text needs a minimum length.', 'wpcf7' ),
						'default'		=> __( 'The text needs a minimum length.', 'wpcf7' )
						) 
					) 
				);
}

/* Tag generator */

add_action( 'admin_init', 'wpcf7_add_tag_generator_min_text_and_email', 15 );

function wpcf7_add_tag_generator_min_text_and_email() 
{
	wpcf7_add_tag_generator( 'text', __( 'Text field', 'wpcf7' ),
		'wpcf7-tg-pane-min-text', 'wpcf7_tg_pane_min_text' );

	wpcf7_add_tag_generator( 'email', __( 'Email field', 'wpcf7' ),
		'wpcf7-tg-pane-min-email', 'wpcf7_tg_pane_min_email' );
}

function wpcf7_tg_pane_min_text( &$contact_form ) 
{
	wpcf7_tg_pane_min_text_and_email( 'text' );
}

function wpcf7_tg_pane_min_email( &$contact_form ) 
{
	wpcf7_tg_pane_min_text_and_email( 'email' );
}

function wpcf7_tg_pane_min_text_and_email( $type = 'text' ) 
{
	if ( 'email' != $type )
		$type = 'text';

?>
<div id="wpcf7-tg-pane-<?php echo $type; ?>" class="hidden">
	<form action="">
<table>
<tr><td><input type="checkbox" name="required" />&nbsp;<?php echo esc_html( __( 'Required field?', 'wpcf7' ) ); ?></td></tr>
<tr><td><?php echo esc_html( __( 'Name', 'wpcf7' ) ); ?><br /><input type="text" name="name" class="tg-name oneline" /></td><td></td></tr>
</table>

<table>
<tr>
<td><code>id</code> (<?php echo esc_html( __( 'optional', 'wpcf7' ) ); ?>)<br />
<input type="text" name="id" class="idvalue oneline option" /></td>

<td><code>class</code> (<?php echo esc_html( __( 'optional', 'wpcf7' ) ); ?>)<br />
<input type="text" name="class" class="classvalue oneline option" /></td>
</tr>

<tr>
<td><code>size</code> (<?php echo esc_html( __( 'optional', 'wpcf7' ) ); ?>)<br />
<input type="text" name="size" class="numeric oneline option" /></td>

<td><code>maxlength</code> (<?php echo esc_html( __( 'optional', 'wpcf7' ) ); ?>)<br />
<input type="text" name="maxlength" class="numeric oneline option" /></td>
</tr>
<tr>
<td></td>
<td><code>minlength</code> (<?php echo esc_html( __( 'optional', 'wpcf7' ) ); ?>)<br />
<input type="text" name="minlength" class="numeric oneline option" /></td>
</tr>

<tr>
<td colspan="2"><?php echo esc_html( __( 'Akismet', 'wpcf7' ) ); ?> (<?php echo esc_html( __( 'optional', 'wpcf7' ) ); ?>)<br />
<?php if ( 'text' == $type ) : ?>
<input type="checkbox" name="akismet:author" class="exclusive option" />&nbsp;<?php echo esc_html( __( "This field requires author's name", 'wpcf7' ) ); ?><br />
<input type="checkbox" name="akismet:author_url" class="exclusive option" />&nbsp;<?php echo esc_html( __( "This field requires author's URL", 'wpcf7' ) ); ?>
<?php else : ?>
<input type="checkbox" name="akismet:author_email" class="option" />&nbsp;<?php echo esc_html( __( "This field requires author's email address", 'wpcf7' ) ); ?>
<?php endif; ?>
</td>
</tr>

<tr>
<td><?php echo esc_html( __( 'Default value', 'wpcf7' ) ); ?> (<?php echo esc_html( __( 'optional', 'wpcf7' ) ); ?>)<br /><input type="text" name="values" class="oneline" /></td>

<td>
<br /><input type="checkbox" name="watermark" class="option" />&nbsp;<?php echo esc_html( __( 'Use this text as watermark?', 'wpcf7' ) ); ?>
</td>
</tr>
</table>

<div class="tg-tag"><?php echo esc_html( __( "Copy this code and paste it into the form left.", 'wpcf7' ) ); ?><br /><input type="text" name="<?php echo $type; ?>" class="tag" readonly="readonly" onfocus="this.select()" /></div>

<div class="tg-mail-tag"><?php echo esc_html( __( "And, put this code into the Mail fields below.", 'wpcf7' ) ); ?><br /><span class="arrow">&#11015;</span>&nbsp;<input type="text" class="mail-tag" readonly="readonly" onfocus="this.select()" /></div>
</form>
</div>
<?php
}
