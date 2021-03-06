<?php
/**
 * @package   AkeebaSubs
 * @copyright Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\Subscriptions\Site\Model\Subscribe;

defined('_JEXEC') or die;

use Akeeba\Subscriptions\Site\Model\Levels;
use FOF30\Container\Container;
use FOF30\Model\Model;

/**
 * A handy class to manage all the data sent to us when submitting the subscription form or when a validation request
 * is made.
 *
 * @package  Akeeba\Subscriptions\Site\Model\Subscribe
 */
class StateData
{
	/** @var   boolean  Is this the first run right after selecting a new subscription level? */
	public $firstrun = false;

	/** @var   string   Subscription level slug */
	public $slug = '';

	/** @var   integer  Subscription level ID */
	public $id = 0;

	/** @var   string   Payment method slug */
	public $paymentmethod = '';

	/** @var   string   Payment processor key */
	public $processorkey = '';

	/** @var   string   Requested username */
	public $username = '';

	/** @var   string   Requested password */
	public $password = '';

	/** @var   string   The repeat of the requested password */
	public $password2 = '';

	/** @var   string   Requested full name of the person */
	public $name = '';

	/** @var   string   Requested email address */
	public $email = '';

	/** @var   string   The repeat of the requested email address */
	public $email2 = '';

	/** @var   string   Requested postal address, first part */
	public $address1 = '';

	/** @var   string   Requested postal address, second part */
	public $address2 = '';

	/** @var   string   Country code (2 letters) */
	public $country = '';

	/** @var   string   State/prefecture/territory code (usually 2 to 10 letters) */
	public $state = '';

	/** @var   string   City */
	public $city = '';

	/** @var   string   ZIP / Postal Code */
	public $zip = '';

	/** @var   integer  Is this a business registration (1) or not (0) */
	public $isbusiness = 0;

	/** @var   string  The business name */
	public $businessname = '';

	/** @var   string  The business activity */
	public $occupation = '';

	/** @var   string  VAT number, without the country prefix */
	public $vatnumber = '';

	/** @var   string  Coupon code */
	public $coupon = '';

	/** @var   array  Per user custom field data */
	public $custom = [];

	/** @var   array  Per subscription custom field data */
	public $subcustom = [];

	/** @var   string  Used in validation requests to define what kind of validation to execute */
	public $opt = '';

	/**
	 * Public constructor. Makes sure the data is loaded on object creation.
	 *
	 * @param   Model $model The parent model calling us, used to fetch the saved state variables
	 */
	public function __construct(Model $model)
	{
		$this->loadData($model);
	}

	/**
	 * Loads the data off the session
	 *
	 * @param   Model $model The parent model calling us, used to fetch the saved state variables
	 *
	 * @return  void
	 */
	public function loadData(Model $model)
	{
		// Is this the first run right after selecting a subscription level?
		$firstRun = $model->getContainer()->platform->getSessionVar('firstrun', true, 'com_akeebasubs');

		if ($firstRun)
		{
			// Reset the first run flag
			$model->getContainer()->platform->setSessionVar('firstrun', false, 'com_akeebasubs');
			/**
			 * Save the level slug, level ID and coupon code
			 *
			 * The first two are required for the priceto be displayed. The latter must survive the reset, otherwise we
			 * will have unhappy clients.
			 */
			$slug   = $model->getState('slug', '', 'string');
			$id     = $model->getState('id', 0, 'int');
			$coupon = $model->getState('coupon', '', 'string');
			// Reset the object parameters
			$this->reset();
			// Re-apply the saved slug, ID and coupon code
			$this->slug   = $slug;
			$this->id     = $id;
			$this->coupon = $coupon;
			// Propagate the object properties to the model's state
			$this->propagateToModelState($model);
		}

		// Apply the state variables from the model
		$stateVars = array(
			'firstrun'      => $firstRun,
			'slug'          => $model->getState('slug', '', 'string'),
			'id'            => $model->getState('id', 0, 'int'),
			'paymentmethod' => $model->getState('paymentmethod', 'none', 'cmd'),
			'processorkey'  => $model->getState('processorkey', '', 'raw'),
			'username'      => $model->getState('username', '', 'string'),
			'password'      => $model->getState('password', '', 'raw'),
			'password2'     => $model->getState('password2', '', 'raw'),
			'name'          => $model->getState('name', '', 'string'),
			'email'         => $model->getState('email', '', 'string'),
			'email2'        => $model->getState('email2', '', 'string'),
			'address1'      => $model->getState('address1', '', 'string'),
			'address2'      => $model->getState('address2', '', 'string'),
			'country'       => $model->getState('country', '', 'cmd'),
			'state'         => $model->getState('state', '', 'cmd'),
			'city'          => $model->getState('city', '', 'string'),
			'zip'           => $model->getState('zip', '', 'string'),
			'isbusiness'    => $model->getState('isbusiness', '', 'int'),
			'businessname'  => $model->getState('businessname', '', 'string'),
			'occupation'    => $model->getState('occupation', '', 'string'),
			'vatnumber'     => $model->getState('vatnumber', '', 'cmd'),
			'coupon'        => $model->getState('coupon', '', 'string'),
			'custom'        => $model->getState('custom', array(), 'raw'),
			'subcustom'     => $model->getState('subcustom', array(), 'raw'),
			'opt'           => $model->getState('opt', '', 'cmd')
		);

		foreach ($stateVars as $k => $v)
		{
			$this->$k = $v;
		}

		unset ($stateVars);

		// Make sure we have a $custom array
		if (!is_array($this->custom))
		{
			$this->custom = [];
		}

		// Make sure we have a $subcustom array
		if (!is_array($this->subcustom))
		{
			$this->subcustom = [];
		}

		// If there is no level ID but there is a slug, use it
		if (empty($this->id) && !empty($this->slug))
		{
			/** @var Levels $levelsModel */
			$levelsModel = $model->getContainer()->factory->model('Levels')->tmpInstance();
			$item = $levelsModel
				->slug([
					'method' => 'exact',
					'value' => $this->slug
				])->firstOrNew();
			$this->id = $item->akeebasubs_level_id;
		}
	}

	/**
	 * Reset the state attributes to their default values. This only affects the StateData object. You need to call
	 * propagateToModelState to reset the Subscribe model we belong to. This is done automatically when the firstrun
	 * flag is set to true in the session.
	 *
	 * In short, if you want to FULLY reset Akeeba Subscriptions' internal validation state you need to set the
	 * firstrun session flag to true BEFORE creating an instance of the Susbcribe model. The model will pick up the
	 * flag's value and create a new StateData object (the class you are looking at right now). The constructor of the
	 * object will call both reset and propagateToModelState, fully resetting the internal state.
	 *
	 * This is done automatically when you pass the reset=1 option in the URL. The onBeforeRead of the Levels controller
	 * will set the firstrun session variable. Moreover, after fetching the blank state, it will propagate the
	 * UserParams (the result of the Users model's getMergedData method) into the data to be displayed in the page. As
	 * a result the user will see the default data, as if they had logged out, flushed all cookies and then logged back
	 * into our site. The only thing that's kept is the coupon code.
	 *
	 * @return  void
	 */
	public function reset()
	{
		$properties = get_object_vars($this);

		foreach ($properties as $k => $v)
		{
			$this->$k = '';
		}

		$this->firstrun = true;
		$this->id = 0;
		$this->isbusiness = 0;
		$this->custom = [];
		$this->subcustom = [];
	}

	/**
	 * Propagates the state variables into the specified model's state.
	 *
	 * This is used when the firstrun flag is set (or when reset=1 is specified in the URL). This lets us reset the
	 * Subscribe model's state to its default, i.e. no data from the user, read everything from the database. That's a
	 * hard reset of the subscription form.
	 *
	 * @param   Model   $model  The model to propagate to.
	 *
	 * @return  void
	 */
	private function propagateToModelState(Model $model)
	{
		$properties = get_object_vars($this);

		foreach ($properties as $k => $v)
		{
			$model->setState($k, $v);
		}
	}
}
