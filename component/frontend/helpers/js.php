<?php
/**
 *  @package AkeebaSubs
 *  @copyright Copyright (c)2010-2014 Nicholas K. Dionysopoulos
 *  @license GNU General Public License version 3, or later
 */

defined('_JEXEC') or die();

class AkeebasubsHelperJs
{
	protected static $selectors = array();

	public static function addSelector($selector)
	{
		self::$selectors[] = $selector;
	}

	public static function deployValidator()
	{
		if(!self::$selectors)
		{
			return;
		}

		$selectors = implode(',', self::$selectors);

		$javascript = <<<ENDJS


// Akeeba Subscriptions --- START >> >> >>
akeeba.jQuery(document).ready(function(){
	akeeba.jQuery('$selectors').change(function(){
		var data = {};
		data.id = akeeba.jQuery('#akeebasubs_level_id').val();

		akeeba.jQuery.each(akeeba.jQuery('$selectors'), function(key, value){
			var element = {};
			var name = akeeba.jQuery(value).attr('name');
			element[name] = akeeba.jQuery(value).val();
			akeeba.jQuery.extend(data, element);
		});

		akeeba.jQuery.ajax('index.php?option=com_akeebasubs&view=validate&opt=plugins&format=json',{
			type : 'POST',
			data : data,
			dataType : 'json',
			success : function(json, textStatus, jqXHR){
				var slavesubsVal = akeeba.jQuery('.slavesubsValidation');
				var i = 0;
				slavesubsVal.hide();

				akeeba.jQuery.each(json.subscription_custom_validation, function(key, value){
					var warn = akeeba.jQuery(slavesubsVal[i]);
					if(!value){
						warn.show();
						warn.closest('div.control-group').addClass('error');
					}
					else{
						warn.hide();
						warn.closest('div.control-group').removeClass('error');
					}
					i += 1;
				})
			}
		});
	})
})
// Akeeba Subscriptions --- END << << <<

ENDJS;

		JFactory::getDocument()->addScriptDeclaration($javascript);
	}
}
