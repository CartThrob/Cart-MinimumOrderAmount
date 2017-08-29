<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cartthrob_minimum_order_amount_ext
{
	public $settings = array();
    public $name = 'CartThrob Minimum Order Amount';
    public $version = '2.0.0';
    public $description = 'Requires the customer to have a minimum order or number of items in their cart before checking out.';
    public $settings_exist = 'y';
    public $docs_url = 'https://github.com/CartThrob/extension-minimum_order_amount/';
	
    protected $EE;

    public function __construct($settings = '')
    {
		$this->settings = $settings;
    }


    public function activate_extension()
    {
	    $this->settings = array(
	        'minimum_subtotal_required_for_checkout'   => "24.99",
	        'minimum_quantity_required_for_checkout'   => "6"
	    );
	
		ee()->db->insert(
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

		ee()->db->insert(
		    'extensions',
		    array(
			'class' => __CLASS__,
			'method' => 'sessions_end',
			'hook' 	=> 'sessions_end',
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

		ee()->db->update(
		    'extensions',
		    array('version' => $this->version),
		    array('class' => __CLASS__)
		);
    }
    public function disable_extension()
    {
	ee()->db->delete('extensions', array('class' => __CLASS__));
    }


	function settings()
	{
	    $settings = array();

	    // Creates a text input with a default value 24.99
	    $settings['minimum_subtotal_required_for_checkout']      = array('i', '', "24.99");
	    $settings['minimum_quantity_required_for_checkout']      = array('i', '', "6");

	    return $settings;
	}

	public function sessions_end()
	{
		ee()->config->_global_vars['ct_minimum_order_setting'] = (float) $this->settings['minimum_subtotal_required_for_checkout'];
		ee()->config->_global_vars['ct_minimum_qty_setting'] = (float) $this->settings['minimum_quantity_required_for_checkout'];
		return;
	}
	
	// this is run whenever the transaction is successful
	function cartthrob_pre_process()
	{
		if (ee()->cartthrob->cart->subtotal() < (float) $this->settings['minimum_subtotal_required_for_checkout'])
		{
			ee()->output->show_user_error('general', lang('minimum_not_met'). $this->settings['minimum_subtotal_required_for_checkout']);
			
		}

		if (ee()->cartthrob->cart->count_all() < (float) $this->settings['minimum_quantity_required_for_checkout'])
		{
			ee()->output->show_user_error('general', lang('minimum_quantity_not_met'). $this->settings['minimum_quantity_required_for_checkout']);
			
		}
				
		return; 
	}
	// END
}
//END CLASS