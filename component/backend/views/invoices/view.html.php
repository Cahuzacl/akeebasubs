<?php
/**
 * @package AkeebaSubs
 * @copyright Copyright (c)2010-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

class AkeebasubsViewInvoices extends FOFViewHtml
{
	protected function onBrowse($tpl = null)
	{
		$model = $this->getModel();

		$this->invoicetemplates = $model->getInvoiceTemplateNames();

		parent::onBrowse($tpl);
	}
}