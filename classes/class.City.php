<?php

class City extends DatabaseObject {

  protected $name;
  
  protected static $table_name = "cities";
  protected static $db_fields = array('id', 'name'); //SHOW COLUMNS FROM sometable

 
}