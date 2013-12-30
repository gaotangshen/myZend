<?php 
namespace User\Model;

 // Add these import statements
 use Zend\InputFilter\InputFilter;
 use Zend\InputFilter\InputFilterAwareInterface;
 use Zend\InputFilter\InputFilterInterface;

 class User implements InputFilterAwareInterface
 {
     public $id;
     public $username;
     public $password;
     protected $inputFilter;                       // <-- Add this variable
     public function exchangeArray($data)
     {
         $this->id     = (isset($data['id']))     ? $data['id']     : null;
         $this->username = (isset($data['username'])) ? $data['username'] : null;
         $this->password  = (isset($data['password']))  ? md5($data['password'])  : null;
     }
     public function setInputFilter(InputFilterInterface $inputFilter)
     {
     	throw new \Exception("Not used");
     }
     
     public function getInputFilter()
     {
//      	if (!$this->inputFilter) {
//      		$inputFilter = new InputFilter();
     
//      		$inputFilter->add(array(
//      				'name'     => 'id',
//      				'required' => true,
//      				'filters'  => array(
//      						array('name' => 'Int'),
//      				),
//      		));
     
//      		$inputFilter->add(array(
//      				'name'     => 'artist',
//      				'required' => true,
//      				'filters'  => array(
//      						array('name' => 'StripTags'),
//      						array('name' => 'StringTrim'),
//      				),
//      				'validators' => array(
//      						array(
//      								'name'    => 'StringLength',
//      								'options' => array(
//      										'encoding' => 'UTF-8',
//      										'min'      => 1,
//      										'max'      => 100,
//      								),
//      						),
//      				),
//      		));
     
//      		$inputFilter->add(array(
//      				'name'     => 'title',
//      				'required' => true,
//      				'filters'  => array(
//      						array('name' => 'StripTags'),
//      						array('name' => 'StringTrim'),
//      				),
//      				'validators' => array(
//      						array(
//      								'name'    => 'StringLength',
//      								'options' => array(
//      										'encoding' => 'UTF-8',
//      										'min'      => 1,
//      										'max'      => 100,
//      								),
//      						),
//      				),
//      		));
     
//      		$this->inputFilter = $inputFilter;
//      	}
     
     	return $this->inputFilter;
     }
 }