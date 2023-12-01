<tr class="wc_shipping_smartpost wc-esm-method--smartpost-courier">
	<th><label for="<?php echo esc_attr( $field_id ) ?>"><?php esc_html_e( 'Choose timewindow', 'wc-estonian-shipping-methods' ) ?></label></th>
	<td>
		<select name="<?php echo esc_attr( $field_name ) ?>" id="<?php echo esc_attr( $field_id ) ?>">
			<option value="" <?php selected( $selected, '' ); ?>><?php echo esc_html_x( '- Choose timewindow -', 'empty value label for courier', 'wc-estonian-shipping-methods' ) ?></option>
		<?php foreach( $windows as $value => $window ) : ?>
			<option value="<?php echo esc_attr( $value ) ?>" <?php selected( $selected, $value ); ?>><?php echo esc_html( $window ) ?></option>
		<?php endforeach; ?>
		</select>
	</td>
</tr>