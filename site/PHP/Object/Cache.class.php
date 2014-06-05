<?php

class Cache {
    public static function clean($pathCache) {
        file_put_contents($pathCache, "");
    }
    
    public static function write($pathCache, $data) {
        file_put_contents($pathCache, $data);
    }
    
    public static function read($pathCache) {
        return file_get_contents($pathCache);
    }
}
