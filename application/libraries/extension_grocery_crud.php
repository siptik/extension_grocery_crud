<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Extension of grocery_CRUD
 *
 * A proper way of extending Grocery CRUD
 *
 * @package    	Extension of grocery_CRUD
 * @copyright  	-
 * @license    	-
 * @version    	1.0
 * @author     	-
 */
class Extension_grocery_CRUD extends grocery_CRUD{
	protected $_ci = null;

	public function __construct(){
		parent::__construct();
		$this->_ci = &get_instance();
	}

	/* Extra field types Functions
     */
    public function field_type_ext($field , $type, $extras = null){
    	if($field && $type){
    		switch ($type) {
    			case 'yes_no':
    				$this->field_type($field,'dropdown', array('1' => 'Yes', '0' => 'No'));
    				break;

    			/*
    			 * If you want to add another field type
    			 * you just set the name in the case and
    			 * the functions inside it
    			 */

    			default:
    				# code...
    				break;
    		}
    	}
    }

    /**********************/

    /* Soft Delete Setter
     * When is called, overrides the default delete function with another that only sets a field named 'deleted' to 1.
     */

    public function set_soft_delete(){
    	$this->callback_delete(array($this,'soft_delete_me'));
    }

    public function soft_delete_me($primary_key){
    	return $this->_ci->db->update($this->basic_db_table,array('deleted' => '1'),array($this->get_primary_key() => $primary_key));
    }
    /************************************************/


    /* APPEND FIELD Functions
	 * 	Append at the End. Eliminate repetitions.
     */
    public function append_fields(){
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}

		$this->add_fields = array_unique(array_merge($this->add_fields,$args));
		$this->edit_fields = array_unique(array_merge($this->edit_fields,$args));

		return $this;
	}

	public function append_add_fields()
	{
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}

		$this->add_fields = array_unique(array_merge($this->add_fields,$args));

		return $this;
	}

	public function append_edit_fields()
	{
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}

		$this->edit_fields = array_unique(array_merge($this->edit_fields,$args));

		return $this;
	}



	/********************************************************/


	/* Prepend FIELD Functions
	 * 	Append at the Beginning. Eliminate repetitions.
     */
	public function prepend_fields()
	{
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}

		$this->add_fields = array_unique(array_merge($args,$this->add_fields));
		$this->edit_fields = array_unique(array_merge($args,$this->edit_fields));

		return $this;
	}

	public function prepend_add_fields()
	{
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}

		$this->add_fields = array_unique(array_merge($args,$this->add_fields));

		return $this;
	}

	public function prepend_edit_fields()
	{
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}

		$this->edit_fields = array_unique(array_merge($args,$this->edit_fields));

		return $this;
	}

	/********************************************************/


	/* Append After FIELD Functions
	 * 	Append after first field in parameters. Eliminate repetitions.
     */

	public function append_fields_after(){
		$args = func_get_args();

		if(func_num_args ()>1){
			$after_field=$args[0];

			if(isset($args[1]) && is_array($args[1])){
				$args = $args[1];
			}else{
				unset($args[0]);
			}


			$this->append_add_fields_after($after_field,$args);
			$this->append_edit_fields_after($after_field,$args);

		}

		return $this;
	}

	public function append_add_fields_after()
	{
		$args = func_get_args();

		if(func_num_args ()>1){
			$after_field=$args[0];

			if(isset($args[1]) && is_array($args[1])){
				$args = $args[1];
			}else{
				unset($args[0]);
			}

			$split_key=array_search($after_field, $this->add_fields);
			if($split_key!==FALSE){
				$add_fields_array=array_diff($this->add_fields, $args);
				$first_fields_list = array_slice($add_fields_array, 0, $split_key+1);
				$middle_fields_list = $args;
				$last_fields_list = array_slice($add_fields_array, $split_key);
				$this->add_fields = array_unique(array_merge($first_fields_list,$middle_fields_list,$last_fields_list));
			}else{
				$this->append_add_fields($args);
			}
		}

		return $this;
	}

	public function append_edit_fields_after()
	{
		$args = func_get_args();

		if(func_num_args ()>1){
			$after_field=$args[0];

			if(isset($args[1]) && is_array($args[1])){
				$args = $args[1];
			}else{
				unset($args[0]);
			}

			$split_key=array_search($after_field, $this->edit_fields);
			if($split_key!==FALSE){
				$edit_fields_array=array_diff($this->edit_fields, $args);
				$first_fields_list = array_slice($edit_fields_array, 0, $split_key+1);
				$middle_fields_list = $args;
				$last_fields_list = array_slice($edit_fields_array, $split_key);
				$this->edit_fields = array_unique(array_merge($first_fields_list,$middle_fields_list,$last_fields_list));
			}else{
				$this->append_edit_fields($args);
			}
		}

		return $this;
	}

	/********************************************************/


	/* APPEND COLUMNS Function
	 * 	Append at the End. Eliminate repetitions.
     */
	public function append_columns(){
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0])){
			$args = $args[0];
		}

		$this->columns = array_unique(array_merge($this->columns,$args));

		return $this;
	}

	/* APPEND COLUMNS Function
	 * 	Append at the Beginning. Eliminate repetitions.
     */
	public function prepend_columns(){
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0])){
			$args = $args[0];
		}

		$this->columns = array_unique(array_merge($args,$this->columns));

		return $this;
	}

    /***************************************/


	/* REMOVE FIELD Functions
	 * 	Removes the fields passed as parameters from the actual field list
     */
    public function remove_fields(){
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}

		$this->add_fields = array_unique(array_diff($this->add_fields,$args));
		$this->edit_fields = array_unique(array_diff($this->edit_fields,$args));

		return $this;
	}

	public function remove_add_fields()
	{
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}

		$this->add_fields = array_unique(array_diff($this->add_fields,$args));

		return $this;
	}

	public function remove_edit_fields()
	{
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}

		$this->edit_fields = array_unique(array_diff($this->edit_fields,$args));

		return $this;
	}
	/********************************************************/


	/* REMOVE COLUMNS Function
	 * 	Removes the columns passed as parameters from the actual columns list
     */
	public function remove_columns(){
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0])){
			$args = $args[0];
		}

		$this->columns = array_unique(array_diff($this->columns,$args));

		return $this;
	}


    /***************************************/
    /***************************************/
    /***************************************/

 	/* EXAMPLE OF BASIC SETUPS USE*/

    public function basic_gc_config($table_name, $content_public_name, $template='twitter-bootstrap'){
    	$this->set_theme($template);

	    $this->set_table($table_name)
        	->set_subject($content_public_name);

		$this->set_soft_delete();

		$this->columns('name','created','public');

		$this->field_type_ext('public','yes_no');


		$this->required_fields('name');

		$this->fields(
			'name',
			'public'
		);

    }
}
