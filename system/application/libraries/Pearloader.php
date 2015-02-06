<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pearloader {
  function load($package, $class,$options = null, $abstract=true){
   //require_once('PEAR/'.$package.'/'.$class.'.php');
   if ($abstract) return true;
   $classname = $package."_".$class;
   if(is_null($options)){
  return new $classname();
   }else{
  return new $classname($options);
   }
  }
}