<?php
defined('_JEXEC') or die();

define('AKEEBASUBS_VERSION', '##VERSION##');
define('AKEEBASUBS_DATE', '##DATE##');
define('AKEEBASUBS_VERSIONHASH', md5(AKEEBASUBS_VERSION.AKEEBASUBS_DATE.JFactory::getConfig()->get('secret','')));