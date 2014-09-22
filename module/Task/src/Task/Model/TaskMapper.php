<?php

namespace Task\Model;

use Task\Model\TaskEntity;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class TaskMapper {
    protected $tableName = 'task';
    protected $dbAdapter = null;
    protected $sql = null;
    
    public function __construct(Adapter $dbAdapter) {
        $this->dbAdapter = $dbAdapter;
        $this->sql = new Sql($dbAdapter);
        $this->sql->setTable($this->tableName);
    }
    
    public function fetchAll($paginated = false) {
    	//create sql select
    	$select = $this->sql->select();
    	$select->order(array('completed ASC', 'created ASC'));
    	
    	//init task entity and hydrator
    	$taskEntity = new TaskEntity();
    	$hydrator = new ClassMethods();
    	$resultset = new HydratingResultSet($hydrator, $taskEntity);
    	
    	if($paginated) { // paginated
    		$paginatorAdapter = new DbSelect($select, $this->dbAdapter, $resultset);
    		return new Paginator($paginatorAdapter);
    	}else {
    		$statement = $this->sql->prepareStatementForSqlObject($select);
    		$results = $statement->execute();
    		$resultset->initialize($results);	
    		return $resultset;
    	}
    }
    
    public function saveTask(TaskEntity $task) {
    	$hydrator = new ClassMethods();
    	$data = $hydrator->extract($task);
    	
    	if($task->getId()) {
    		//update
    		$action = $this->sql->update();
    		$action->set($data);
    		$action->where(array('id' => $task->getId()));
    	} else {
    		//insert
    		$action = $this->sql->insert();
    		unset($data['id']);
    		$action->values($data);
    	}
    	
    	//do execute
    	$statement = $this->sql->prepareStatementForSqlObject($action);
    	$result = $statement->execute();
    	
    	if(!$task->getId()) {
    		$task->setId($result->getGeneratedValue());
    	}
    	
    	return $result;
    }
    
    public function getTask($id)
    {
    	$select = $this->sql->select();
    	$select->where(array('id' => $id));
    
    	$statement = $this->sql->prepareStatementForSqlObject($select);
    	$result = $statement->execute()->current();
    	if (!$result) {
    		return null;
    	}
    
    	$hydrator = new ClassMethods();
    	$task = new TaskEntity();
    	$hydrator->hydrate($result, $task);
    
    	return $task;
    }
    
    public function deleteTask($id)
    {
    	$delete = $this->sql->delete();
    	$delete->where(array('id' => $id));
    
    	$statement = $this->sql->prepareStatementForSqlObject($delete);
    	return $statement->execute();
    }
}
