<?php

namespace Task\Model;

class TaskEntity {
    
    protected $id;
    
    protected $title;
    
    protected $completed = 0;
    
    protected $created;
    
    public function __construct() {
        $this->created = date('Y-m-d H:i:s');
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function getTitle() {
        return $this->title;
    }
    
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }
    
    public function getCompleted() {
        return $this->completed;
    }
    
    public function setCompleted($completed) {
        $this->completed = $completed;
        return $this;
    }
    
    public function getCreated() {
        return $this->created;
    }
    
    public function setCreated($created) {
        $this->created = $created;
        return $this;
    }
}