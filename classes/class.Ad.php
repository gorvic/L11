<?php

class Ad extends DatabaseObject {

  protected static $table_name = "ads";
  protected static $db_fields = array('id', 
									  'seller_name',
									  'phone',
									  'allow_mails',
									  'category_id',
									  'location_id',
									  'title',
									  'description',
									  'price',
									  'email',
									  'organization_form_id',
									 ); //SHOW COLUMNS FROM sometable

  public $id;
  public $seller_name;
  public $phone;
  public $allow_mails;
  public $category_id;
  public $location_id;
  public $title;
  public $description;
  public $price;
  public $email;
  public $organization_form_id;

  
  
}
