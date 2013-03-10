<?php
class ElggFirePHP extends FirePHP {
	
	/**
	 * @see ElggFirePHP::getDefaultOptions
	 * @var array
	 */
	private $optionsCache = null;
	
	/**
	 * Creates FirePHP object and stores it for singleton access
	 *
	 * @return FirePHP
	 */
	public static function init() {
		return self::$instance = new self();
	}
	
	/**
	 * Gets singleton instance of FirePHP
	 *
	 * @see FirePHP::getInstance
	 * @param boolean $AutoCreate
	 * @return ElggFirePHP
	 */
	public static function getInstance($AutoCreate=false) {
		if($AutoCreate===true && !self::$instance) {
			self::init();
		}
		return self::$instance;
	}
	
	/**
	 * Get options to be set to this instance on registerHandlers
	 * @see ElggFirePHP::registerHandlers
	 */
	public function getDefaultOptions() {
		if ($this->optionsCache !== null) {
			return $this->optionsCache;
		}
		//@todo consider customizing these via plugin settings
		$this->optionsCache = array(
			'maxDepth' => 3,//watch out, highet values tend to timeout on conversion of extensive data
			'maxObjectDepth' => 5,
			'maxArrayDepth' => 5,
			'useNativeJsonEncode' => true,
			'includeLineNumbers' => true,
		);
		$plugin_name = basename(dirname(dirname(__FILE__)));
		foreach (array_keys($this->optionsCache) as $key) {
			$val = elgg_get_plugin_setting($key, $plugin_name);
			if ($val !== null) {
				$this->optionsCache[$key] = $val;
			}
		}
		return $this->optionsCache;
	}
	
	/**
	 * Tap into default Elgg error/exception handers
	 */
	public function registerHandlers() {
		if ($this->detectClientExtension()) {
			//report everything to the console
			error_reporting(E_ALL);
			$this->setOptions($this->getDefaultOptions());

			//remove Elgg core handlers
			restore_error_handler();
			restore_exception_handler();

			//initialize FirePHP
			$this->registerErrorHandler();
			$this->registerExceptionHandler();
		}
	}
	
	/**
	 * FirePHP's error handler
	 *
	 * Throws exception for each php error that will occur.
	 *
	 * @see FirePHP::errorHandler
	 * @param int $errno
	 * @param string $errmsg
	 * @param string $filename
	 * @param int $linenum
	 * @param array $vars
	 */
	public function errorHandler($errno, $errmsg, $filename, $linenum, $vars) {
		parent::errorHandler($errno, $errmsg, $filename, $linenum, $vars);
		return _elgg_php_error_handler($errno, $errmsg, $filename, $linenum, $vars);
	}
	
	/**
	 * FirePHP's exception handler
	 *
	 * Logs all exceptions to your firebug console and then stops the script.
	 *
	 * @see FirePHP::exceptionHandler
	 * @param Exception $exception
	 * @throws Exception
	 */
	function exceptionHandler($exception) {
		parent::exceptionHandler($exception);
		return _elgg_php_exception_handler($exception);
	}
}