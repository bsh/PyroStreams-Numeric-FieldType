<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Numeric Field Type
 *
 * @author		Laszlo Kovacs
 */
class Field_numeric
{
	public $field_type_name			= 'Numeric';

	public $field_type_slug			= 'numeric';
	
	public $db_col_type				= 'varchar';
	
	public $extra_validation 		= 'numeric'; 
	
	public $version					= '1.0.0';

	public $custom_parameters		= array('default_value', 'min_length', 'max_length');

	public $author					= array('name'=>'Laszlo Kovacs', 'url'=>'http://xmeditor.hu');

	/**
	 * Process before saving to database
	 *
	 * @access	public
	 * @param	string
	 * @param	object
	 * @return	string
	 */
	public function pre_save($input, $field)
	{
		return $this->prep($input, $field->field_data['decimal_places']);
	}

	/**
	 * Validate input
	 *
	 * @param	string
	 * @param	string - mode: edit or new
	 * @param	object
	 * @return	mixed - true or error string
	 */
	public function validate($value, $mode, $field)
	{
		if ( isset($field->field_data['max_length']) and strlen($field->field_data['max_length']) > 0 and strlen($value) > $field->field_data['max_length'])
		{
			return lang('streams:numeric.max_length_error');
		}

		if ( isset($field->field_data['min_length']) and strlen($field->field_data['min_length']) > 0 and strlen($value) < $field->field_data['min_length'])
		{
			return lang('streams:numeric.min_length_error');
		}
		return true;
	}	

	/**
	 * Process before outputting
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @return	string
	 */
	public function pre_output($input, $data)
	{
		return $this->prep($input, $data['decimal_places']);
	}

	/**
	 * Output the form input
	 *
	 * @access	public
	 * @param	array
	 * @param	int
	 * @param	object
	 * @return	string
	 */
	public function form_output($data, $id = false, $field)
	{
		$options['name'] 	= $data['form_slug'];
		$options['id']		= $data['form_slug'];
		$options['value']	= (!empty($data['value'])) ? $this->prep($data['value']) : $this->prep($field->field_data['default_value']);
		
		return form_input($options);
	}

	/**
	 * Min length?
	 *
	 * @return	string
	 */
	public function param_min_length( $value = null )
	{
		return form_input('min_length', $value);
	}

	/**
	 * Max length?
	 *
	 * @return	string
	 */
	public function param_max_length( $value = null )
	{
		return form_input('max_length', $value);
	}

	/**
	 * Strip it down
	 *
	 * @access	public
	 * @param	string
	 * @return	param_min_length
	 */
	private function prep($value)
	{
		return preg_replace('/\D/', '', $value);
	}

}