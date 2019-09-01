<?php

namespace humhub\modules\space\widgets;

use humhub\components\Widget;

class SpaceNameColorInputCommunity extends Widget
{
    
    public $model;
    public $form;

    /**
     * Displays / Run the Widgets
     */
    public function run()
    {
        return $this->render('spaceNameColorInputCommunity', [
                    'model' => $this->model,
                    'form' => $this->form
        ]);
    }
}
