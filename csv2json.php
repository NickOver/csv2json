<?php

	class csv2json {

		private $fieldSeparator = ',';
		private $rowSeparator = ';';
		private $avoidFieldNumbers = null;
		private $firstRowAsKey = true;

		public function __construct($configArray = null){
			if($configArray != null){
				try {
					if(!is_array($configArray))
						throw new exception('Invalid parameter type');
					foreach($configArray as $property => $value){
						$this->setValue($property, $value);
					}
				} catch (exception $ex) {
					echo $ex->getMessage().PHP_EOL;
				}
			}
		}

		public function setValue($property, $value){
			$this->isValidConfigData($property, $value);
			$this->$property = $value;
		}

		private function isValidConfigData($property, $value){
			if(!array_key_exists($property, get_object_vars($this)))
				throw new exception($property.' is not valid property name');
			if(!is_string($value) AND !in_array($property, array('firstRowAsKey')))
				throw new exception(var_export($value, true).' is not valid property value');
		}

		public function getJson($csvString){
			try {
				$this->isValidCsvString($csvString);
				$array = $this->prepareArray($csvString);
				return json_encode($array);
			} catch (exception $ex) {
				echo $ex->getMessage().PHP_EOL;
			}
		}

		private function isValidCsvString($csvString){
			if(!is_string($csvString))
				throw new exception('This isn\'t string');
			if(substr_count($csvString, $this->rowSeparator) < 1 + $this->firstRowAsKey)
				throw new exception('Given string doesn\'t have valid row amounts');

		}

		private function prepareArray($csvString){
			$this->rows = explode($this->rowSeparator, $csvString);
			$this->avoidFieldsArray = explode(',', $this->avoidFieldNumbers);
			if($this->firstRowAsKey){
				$this->keys = explode($this->fieldSeparator, trim($this->rows[0]));
				$this->keys = $this->unsetAvoidingKeys($this->keys);
			}
			for($i = 0 + $this->firstRowAsKey; $i < count($this->rows); $i++){
				$array[] = $this->getArrayElement($i);
			}
			return $array;
		}

		private function unsetAvoidingKeys($rowArray){
			if(count($this->avoidFieldsArray) > 0){
				foreach($this->avoidFieldsArray as $key => $avoidingKeyNo){
					if(isset($rowArray[$avoidingKeyNo]))
						unset($rowArray[$avoidingKeyNo]);
				}
			}
			return $rowArray;
		}

		private function getArrayElement($rowNumber){
			$fields = explode($this->fieldSeparator, trim($this->rows[$rowNumber]));
			if($this->firstRowAsKey){
				foreach($this->keys as $key => $fieldName){
					$fieldsWithKeys[$fieldName] = (isset($fields[$key]))? $fields[$key] : null;
				}
				return $fieldsWithKeys;
			} else {
				return $this->unsetAvoidingKeys($fields);
			}
		}

	}
