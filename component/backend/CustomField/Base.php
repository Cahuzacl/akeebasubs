<?php
/**
 * @package        akeebasubs
 * @copyright      Copyright (c)2010-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

namespace Akeeba\Subscriptions\Admin\CustomField;

use Akeeba\Subscriptions\Admin\Model\CustomFields;
use FOF30\Container\Container;
use FOF30\Inflector\Inflector;
use stdClass;

defined('_JEXEC') or die();

/**
 * Abstract base class for custom per-user and per-subscription fields
 *
 * @since 2.6.0
 */
abstract class Base
{

	/** The field type, set by the constructor */
	protected $fieldType = null;

	/**
	 * Create a custom field object instance
	 *
	 * @param   array $config Custom configuration parameters
	 */
	public function __construct(array $config = array())
	{
		// Set up the field type
		if (!isset($config['field_type']))
		{
			if (empty($this->fieldType))
			{
				$container            = Container::getInstance('com_akeebasubs');
				$parts                = $container->inflector->explode(get_called_class());
				$type                 = strtolower(array_pop($parts));
				$config['field_type'] = $type;
			}
			else
			{
				$config['field_type'] = $this->fieldType;
			}
		}
		$this->fieldType = $config['field_type'];
	}

	/**
	 * Creates a custom field array which will be used by the renderer to be
	 * shown on the front- or back-end page, for per-user fields
	 *
	 * @param   CustomFields  $item        A custom field definition
	 * @param   array         $cache       The values cache
	 * @param   stdClass      $userparams  User parameters
	 *
	 * @return  array  Field definition
	 */
	public function getField($item, $cache, $userparams)
	{
		return null;
	}

	/**
	 * Create the necessary Javascript and add it to the page
	 *
	 * @param   CustomFields  $item  The item to render the Javascript for
	 *
	 * @return  string  Javascript to add to the page
	 */
	public function getJavascript($item)
	{
		return null;
	}

	/**
	 * Validate a per-user field
	 *
	 * @param   CustomFields  $item    The custom field to validate
	 * @param   array         $custom  The custom fields' values array
	 *
	 * @return  int  1 if the field is valid, 0 otherwise, null if not supported
	 */
	public function validate($item, $custom)
	{
		return null;
	}

	/**
	 * Creates a custom field array which will be used by the renderer to be
	 * shown on the front- or back-end page, for per subscription fields
	 *
	 * @param   CustomFields  $item   A custom field definition
	 * @param   array         $cache  The values cache
	 *
	 * @return  array  Field definition
	 */
	public function getPerSubscriptionField($item, $cache)
	{
		return null;
	}

	/**
	 * Validate a per-subscription field
	 *
	 * @param   CustomFields  $item    The custom field to validate
	 * @param   array         $custom  The custom fields' values array
	 *
	 * @return  int  1 if the field is valid, 0 otherwise, null if not supported
	 */
	public function validatePerSubscription($item, $custom)
	{
		return null;
	}

	/**
	 * Return price modifiers
	 *
	 * @param   CustomFields  $item  The custom field to validate
	 * @param   array         $data  The data coming from the form
	 *
	 * @return  float  How much we should add/remove from the price or 0/null when no modification is required
	 */
	public function validatePrice($item, $data)
	{
		return null;
	}

	/**
	 * Return subscription length modifiers
	 *
	 * @param   CustomFields   $item  The custom field to validate
	 * @param   array          $data  The data coming from the form
	 *
	 * @return  int  How much we should add/remove from the subscription length (in days) or 0/null when no modification
	 *               is required
	 */
	public function validateLength($item, $data)
	{
		return null;
	}
}