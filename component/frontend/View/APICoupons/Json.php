<?php
/**
 * @package		Akeeba Subscriptions
 * @copyright	2015 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

namespace Akeeba\Subscriptions\Site\View\APICoupons;

use Akeeba\Subscriptions\Site\Model\APICoupons;

class Json extends \FOF30\View\DataView\Json
{
	protected function onBeforeCreate($tpl = null)
	{
		/** @var APICoupons $model */
		$model = $this->getModel();

		// Get the key and password
		$key = $this->input->getCmd('key', '');
		$pwd = $this->input->getCmd('pwd', '');

		// Create the coupon and set the response into $this->item
		$this->item = $model->createCoupon($key, $pwd);
		$this->alreadyLoaded = true;
		$this->useHypermedia = false;

		// Call the parent's onBeforeRead which handles the output
		parent::onBeforeRead($tpl);
	}
}