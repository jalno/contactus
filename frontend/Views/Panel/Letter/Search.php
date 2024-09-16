<?php

namespace themes\clipone\Views\Contactus\Panel\Letter;

use packages\base\View\Error;
use packages\contactus\Letter;
use packages\contactus\Views\Panel\Letter\Search as SearchList;
use themes\clipone\Navigation;
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
            t('contactus'),
            t('list'),
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

    public function setButtons()
    {
        $this->setButton('view', $this->canView, [
            'title' => t('view'),
            'icon' => 'fa fa-files-o',
            'classes' => ['btn', 'btn-xs', 'btn-green'],
        ]);
        $this->setButton('delete', $this->canDel, [
            'title' => t('delete'),
            'icon' => 'fa fa-times',
            'classes' => ['btn', 'btn-xs', 'btn-bricky'],
        ]);
    }

    protected function getComparisonsForSelect()
    {
        return [
            [
                'title' => t('search.comparison.contains'),
                'value' => 'contains',
            ],
            [
                'title' => t('search.comparison.equals'),
                'value' => 'equals',
            ],
            [
                'title' => t('search.comparison.startswith'),
                'value' => 'startswith',
            ],
        ];
    }

    protected function getStatusForSelect()
    {
        return [
            [
                'title' => t('contactus.search.choose'),
                'value' => '',
            ],
            [
                'title' => t('contactus.letter.unread'),
                'value' => Letter::unread,
            ],
            [
                'title' => t('contactus.letter.read'),
                'value' => Letter::read,
            ],
            [
                'title' => t('contactus.letter.answered'),
                'value' => Letter::answered,
            ],
        ];
    }
}
