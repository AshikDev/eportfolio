<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

/**
 * Created by PhpStorm.
 * User: davidborn
 */

namespace humhub\modules\tasks\widgets;


use humhub\components\Widget;
use humhub\modules\tasks\models\Task;

class TaskIcon extends Widget
{
    /**
     * @var Task
     */
    public $task;


    public $include;

    public $includeOverdue = true;

    public function init()
    {
        if(empty($this->include)) {
            $this->include = Task::$statuses;
        }
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function run()
    {
        return $this->render('taskIcon', [
            'task' => $this->task,
            'include' => $this->include,
            'includeOverdue' => $this->includeOverdue,
        ]);
    }

}