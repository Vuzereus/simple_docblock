<?php
class MyClass 
{	private $name;
	public function __construct($appName) 
	{		$this->name = $appName;
	}
	public function getAppName() 
	{
return $this->name;}
	public function run($config) 
	{
		// Your application logic goes here.
	}
}         function initializeApp($appName, $config) {	// Initialize the application here.
}
	//check variable type
	function checkType($var)
	{
		switch (gettype($var)) {case "resource":
				$this->varIsResource($var);          break;
			case "object":	$this->varIsObject($var);
				break;		case "array":
				$this->varIsArray($var);
				break;case "boolean":
				$this->varIsBoolean($var);
				break;	default:
				$var = ($var == "") ? "[empty string]" : $var;
				$this->output .= "<table cellspacing=0><tr>\n<td>" . $var . "</td>\n</tr>\n</table>\n";
				break;	}}
