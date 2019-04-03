<?php
namespace Kanboard\Plugin\AutomaticAction\Action;
use Kanboard\Model\TaskModel;
use Kanboard\Action\Base;
/**
 * Send Conclusion E-mail for Task Moved to Done Column 
 *
 * Original author  Frederic Guillot
 * From https://github.com/kanboard/kanboard/blob/master/app/Action/TaskEmail.php
 *
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
        return t('Send Conclusion Default Email for Task Moved to Done Column');
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
            'body_message' => t('Email body message'),
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
     * Execute the action (move the task to another column)
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool            True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        $user = $this->userModel->getById($this->getParam('user_id'));
        if (! empty($user['email'])) {
            $this->emailClient->send(
                $user['email'],
                $user['name'] ?: $user['username'],
                $this->getParam('subject'),
                $this->template->render('notification/task_create', array(
                    'task' => $data['task'],
                ))
            );
            return true;
        }
        return false;
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
        return $data['task']['column_id'] == $this->getParam('column_id');
    }
}
