<?php

class Application_Form_Video extends Zend_Form
{

    public function __construct($param)
    {
        $this->addElement($this->input_empty('video_title','Video title not null !'));
        $this->addElement($this->input_empty('video_url_video_hidden','Video file upload not null !'));
        $this->addElement($this->input_empty('video_url_img_hidden','Video file images alias not null !'));
        $this->addElement($this->input_empty('video_description','Video description not null !'));
    }

    protected function input_empty($name, $messenger = 'INPUT_EMPTY')
    {
        $element = $this->getElement($name);
        !$element ? $element = new Zend_Form_Element_Text($name)
            : null;
        $element->setRequired(true)
            ->addValidator(
                'NotEmpty', true,
                ['messages' => ['isEmpty' => $messenger]]
            );
        return $element;
    }
}

