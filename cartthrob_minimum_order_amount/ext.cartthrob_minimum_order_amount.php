<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cartthrob_minimum_order_amount_ext
{
	public $settings = array();
    public $name = 'CartThrob Minimum Order Amount';
    public $version = '1.0.1';
    public $description = 'Requires customer to have a minimum order in basket before checking out.';
    public $settings_exist = 'y';
    public $docs_url = 'http://barrettnewton.com';
	
    protected $EE;

    public function __construct($settings = '')
    {
	$this->EE =& get_instance();
	$this->settings = $settings;
    }


    public function activate_extension()
    {
	    $this->settings = array(
	        'minimum_subtotal_required_for_checkout'   => "24.99",
	    );
	
		$this->EE->db->insert(
		    'extensions',
		    array(
			'class' => __CLASS__,
			'method' => 'cartthrob_pre_process',
			'hook' 	=> 'cartthrob_pre_process',
			'settings' => '',
			'priority' => 10,
			'version' => $this->version,
			'enabled' => 'y'
		    )
		);
	}
    public function update_extension($current='')
    {
		if ($current == '' OR $current == $this->version)
		{
		    return FALSE;
		}

		$this->EE->db->update(
		    'extensions',
		    array('version' => $this->version),
		    array('class' => __CLASS__)
		);
    }
    public function disable_extension()
    {
	$this->EE->db->delete('extensions', array('class' => __CLASS__));
    }


	function settings()
	{
	    $settings = array();

	    // Creates a text input with a default value 24.99
	    $settings['minimum_subtotal_required_for_checkout']      = array('i', '', "24.99");

	    return $settings;
	}
	
	// this is run whenever the transaction is successful
	function cartthrob_pre_process()
	{
		if ($this->EE->cartthrob->cart->subtotal() < (float) $this->settings['minimum_subtotal_required_for_checkout'])
		{
			$this->EE->output->show_user_error('general', lang('minimum_not_met'). $this->settings['minimum_subtotal_required_for_checkout']);
			
		}
		return; 
	}
	// END
}
//END CLASS