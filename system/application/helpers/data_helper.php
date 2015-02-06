<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('import_data')) {

  function import_data(&$object, $data)
  {
    if (is_object($data)) {
      $data = get_object_vars($data);
    }
    if (is_array($data)) {
      foreach ($data as $property => $value) {
        if (property_exists($object, $property)) {
          $object->$property = $value;
        }
      }
    }
    return $object;
  }

}