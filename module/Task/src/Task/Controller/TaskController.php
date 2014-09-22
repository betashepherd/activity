<?php

/**
 * activity controller
 * 
 * @author rap
 * @copyright www.activity.com
 * @version $Id$
 */
namespace Task\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Task\Form\TaskForm;
use Task\Model\TaskEntity;

class TaskController extends AbstractActionController
{
    protected $_taskMapper = null;
    
    public function indexAction()
    {
        $tasks = $this->getTaskMapper()->fetchAll(true);
        $tasks->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        
        $tasks->setItemCountPerPage(1);
        
        return new ViewModel(
            array('tasks' => $tasks)
        );
    }

    public function addAction()
    {
    	$form = new TaskForm();
    	$task = new TaskEntity();
    	$form->bind($task);
    	
    	$request = $this->getRequest();
    	if($request->isPost()) {
    		$form->setData($request->getPost());
    		if($form->isValid()) {
    			$taskMapper = $this->getTaskMapper();
    			$taskMapper->saveTask($task);
    			return $this->redirect()->toRoute('task');
    		}
    	}
        return new ViewModel(array('form' => $form));
    }

	 public function editAction()
	 {
	     $id = (int)$this->params('id');
	     if (!$id) {
	         return $this->redirect()->toRoute('task', array('action'=>'add'));
	     }
	     $task = $this->getTaskMapper()->getTask($id);
	
	     $form = new TaskForm();
	     $form->bind($task);
	
	     $request = $this->getRequest();
	     if ($request->isPost()) {
	         $form->setData($request->getPost());
	         if ($form->isValid()) {
	             $this->getTaskMapper()->saveTask($task);
	
	             return $this->redirect()->toRoute('task');
	         }
	     }
	
	     return array(
	         'id' => $id,
	         'form' => $form,
	     );
	 }

	public function delAction()
	 {
	     $id = $this->params('id');
	     $task = $this->getTaskMapper()->getTask($id);
	     if (!$task) {
	         return $this->redirect()->toRoute('task');
	     }
	
	     $request = $this->getRequest();
	     if ($request->isPost()) {
	         if ($request->getPost()->get('del') == 'Yes') {
	             $this->getTaskMapper()->deleteTask($id);
	         }
	
	         return $this->redirect()->toRoute('task');
	     }
	
	     return array(
	         'id' => $id,
	         'task' => $task
	     );
	 }
    
    protected function getTaskMapper() 
    {
        $sm = $this->getServiceLocator();
        $this->_taskMapper = $sm->get('TaskMapper');
        return $this->_taskMapper;
    }

}

