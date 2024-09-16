<?php
namespace themes\clipone\Listeners\Contactus;

use packages\contactus\Authorization;
use themes\clipone\Navigation;
use themes\clipone\Navigation\MenuItem;

use function packages\userpanel\url;

class NavigationListener
{
	public function initial(): void
	{
		if (Authorization::is_accessed('letter_search')) {
			$item = new MenuItem('contactus');
			$item->setTitle(t('contactus'));
			$item->setURL(url('contactus'));
			$item->setIcon('fa fa-envelope');
			Navigation::addItem($item);
		}
	}
}