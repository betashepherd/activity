<?php

namespace Album\Model;

/**
 * @author rap
 *
 */
class Album {
    
    public $id;
    public $title;
    public $artist;
    
    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->artist = (!empty($data['artist'])) ? $data['artist'] : null;
    }
}