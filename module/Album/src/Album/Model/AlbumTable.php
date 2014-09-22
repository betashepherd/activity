<?php

namespace Album\Model;

use Zend\Db\TableGateway\TableGateway;

class AlbumTable {
    protected $tableGateway = null;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    public function fetchAll() {
        return $this->tableGateway->select();
    }
    
    public function getAlbum($id) {
        $id = (int) $id;
        
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        
        if(!$row) {
            throw new \Exception("Could not find row $id");
        }
        
        return $row;
    }
    
    public function saveAlbum(Album $album) {
        $data = array(
            'title'  => $album->title,
            'artist' => $album->artist
        );
        
        $id = (int) $album->id;
        
        if ($id === 0) { // album not exists
            $this->tableGateway->insert($data);
        } else {
            if( $this->getAlbum($id) ) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception("Album not exist");
            }
        }
    }
    
    public function deleteAlbum($id) {
        $id = (int) $id;
        $this->tableGateway->delete(array('id' => $id));
    }
}