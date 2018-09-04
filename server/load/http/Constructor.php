<?php
# Prevent access from direct calls
defined('SN_Start') or header('HTTP/1.1 404 Not Found');

namespace Superior\Http;

use Exception;
use Superior\Request;
use SN\Management as SN;

class Constructor {
	public $request;
	public $augments = NULL;
	
	function __construct(Request $request, Array $request_array) {
	#	remove a router's helper
	#	unset($request_array['p']);
		
		$this->request = (object) $request_array;
	}
	
	public function get() {
		return $this->request;
	}
	
	public function getAugments() {
		if (is_null($this->augments)) {
			try {
				$this->BuildAugmentation();
			} catch (Exception $e) {
				SN::NewErr();
				SN::ExplainLast($e->getMessage());
				$this->augments = false;
				return false;
			}
		}
		return $this->augments;
	}
	
	public function isAugmentsPresent() {
		if (is_null($this->augments)) {
			try {
				$this->BuildAugmentation();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}
		return false;
	}
	
	private function BuildAugmentation() {
		$errorMsg = 'Request augmentation is missing. Trying to get properties will provide an error. Please use special methods to verify augmentation before using it.';
		if (isset($_REQUEST['augmentation']) && $_REQUEST['augmentation']!='') {
			$aug = explode('/', trim($_REQUEST['augmentation'], '/'));
			if (count($aug) < 1) {
				throw new Exception($errorMsg);
			}
			$this->augments = (object) $aug;
			unset($request_array['augmentation']);
			return;
		}
		throw new Exception($errorMsg);
	}
}