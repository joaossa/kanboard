<?php

namespace Kanboard\Plugin\AutomaticAction;

use Kanboard\Core\Plugin\Base;
use Kanboard\Plugin\AutomaticAction\Action\TaskSendConclusionEmail;

class Plugin extends Base
{
    public function initialize()
    {
        $this->actionManager->register(new TaskSendConclusionEmail($this->container));
    }
}
