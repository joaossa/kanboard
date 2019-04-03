<?php
namespace Kanboard\Plugin\AutomaticAction\Action;
use Kanboard\Model\TaskModel;
use Kanboard\Action\Base;
/**
 * Send Conclusion E-mail for Task Moved to Done Column 
 * https://github.com/joaossa/kanboard/blob/master/Action/TaskSendConclusionEmail.php
 * @package action
 * @author  Joao Freire
 */
class TaskSendConclusionEmail extends Base
{
    /**
     * Get automatic action description
     *
     * @access public
     * @return string
     */
    public function getDescription()
    {
        return t('Send conclusion default e-mail message when the task is moved to done column');
    }
    /**
     * Get the list of compatible events
     *
     * @access public
     * @return array
     */
    public function getCompatibleEvents()
    {
        return array(
            TaskModel::EVENT_MOVE_COLUMN,
            TaskModel::EVENT_CLOSE,
            TaskModel::EVENT_CREATE,
        );
    }
    /**
     * Get the required parameter for the action (defined by the user)
     *
     * @access public
     * @return array
     */
    public function getActionRequiredParameters()
    {
        return array(
            'column_id' => t('Column'),
            'user_id' => t('User that will receive the email'),
            'subject' => t('Email subject'),
            'body_text' => t('Email body message'),
        );
    }
    /**
     * Get the required parameter for the event
     *
     * @access public
     * @return string[]
     */
    public function getEventRequiredParameters()
    {
        return array(
            'task_id',
            'task' => array(
                'project_id',
                'column_id',
        );
    }
    /**
     * Execute the action
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool            True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        return $this->taskModificationModel->update(array('id' => $data['task_id'], 'title' => $this->getParam('title')));
    }
    /**
     * Check if the event data meet the action condition
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool
     */
    public function hasRequiredCondition(array $data)
    {
        return true;
    }
}
