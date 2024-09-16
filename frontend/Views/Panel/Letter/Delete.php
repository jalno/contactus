<?php

namespace themes\clipone\Views\Contactus\Panel\Letter;

use packages\base\Translator;
use packages\contactus\Views\Panel\Letter\Delete as LetterDelete;
use themes\clipone\Navigation;
use themes\clipone\Views\FormTrait;
use themes\clipone\ViewTrait;

class Delete extends LetterDelete
{
    use ViewTrait;
    use FormTrait;
    protected $letter;

    public function __beforeLoad()
    {
        $this->letter = $this->getLetter();
        $this->setTitle([
            t('contactus'),
            t('letter_delete'),
        ]);
        Navigation::active('contactus');
    }
}
