<?php

namespace themes\clipone\Views\Contactus\Panel\Letter;

use packages\base\Translator;
use packages\base\View\Error;
use packages\contactus\Letter;
use packages\contactus\Views\Panel\Letter\Search as SearchList;
use packages\userpanel;
use themes\clipone\Navigation;
use themes\clipone\Navigation\MenuItem;
use themes\clipone\Views\FormTrait;
use themes\clipone\Views\ListTrait;
use themes\clipone\ViewTrait;

class Search extends SearchList
{
    use ViewTrait;
    use FormTrait;
    use ListTrait;

    public function __beforeLoad()
    {
        $this->setTitle([
            Translator::trans('contactus'),
            Translator::trans('list'),
        ]);
        Navigation::active('contactus');
        $this->setButtons();
        if (empty($this->getLetters())) {
            $this->addNotFoundError();
        }
    }

    private function addNotFoundError()
    {
        $error = new Error();
        $error->setType(Error::NOTICE);
        $error->setCode('contactus.letter.notfound');
        $this->addError($error);
    }

    public static function onSourceLoad()
    {
        parent::onSourceLoad();
        if (self::$navigation) {
            $item = new MenuItem('contactus');
            $item->setTitle(Translator::trans('contactus'));
            $item->setURL(userpanel\url('contactus'));
            $item->setIcon('fa fa-envelope');
            Navigation::addItem($item);
        }
    }

    public function setButtons()
    {
        $this->setButton('view', $this->canView, [
            'title' => Translator::trans('view'),
            'icon' => 'fa fa-files-o',
            'classes' => ['btn', 'btn-xs', 'btn-green'],
        ]);
        $this->setButton('delete', $this->canDel, [
            'title' => Translator::trans('delete'),
            'icon' => 'fa fa-times',
            'classes' => ['btn', 'btn-xs', 'btn-bricky'],
        ]);
    }

    protected function getComparisonsForSelect()
    {
        return [
            [
                'title' => Translator::trans('search.comparison.contains'),
                'value' => 'contains',
            ],
            [
                'title' => Translator::trans('search.comparison.equals'),
                'value' => 'equals',
            ],
            [
                'title' => Translator::trans('search.comparison.startswith'),
                'value' => 'startswith',
            ],
        ];
    }

    protected function getStatusForSelect()
    {
        return [
            [
                'title' => Translator::trans('contactus.search.choose'),
                'value' => '',
            ],
            [
                'title' => Translator::trans('contactus.letter.unread'),
                'value' => Letter::unread,
            ],
            [
                'title' => Translator::trans('contactus.letter.read'),
                'value' => Letter::read,
            ],
            [
                'title' => Translator::trans('contactus.letter.answered'),
                'value' => Letter::answered,
            ],
        ];
    }
}
