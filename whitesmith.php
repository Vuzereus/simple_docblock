<?php



function formatPHPFile()
{
	$source_filename = 'source.php';
	$target_filename = 'target.php';
    // Read the PHP file
    $content = file_get_contents($source_filename);
	
	// ##############################################################################
	//		Remove
	// ##############################################################################
	//$pattern = '/\/\/(.*)/';
	//$content = preg_replace_callback($pattern, 'replaceSingleLineComment', $content);
	
	// This pattern uses a regex alternation (|) to match either quoted strings or whitespace.
	// Callback function 'preserveQuotedStrings' to preserving content within 
	// double quotes (""), single quotes (''), and comments (// and /* ... */)
	//$pattern = '/("[^"\\\\]*(?:\\\\.[^"\\\\]*)*"|\'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\'|\/\/[^\n]*|\/\*.*?\*\/)|\s+/s';
	//$content = preg_replace_callback($pattern, 'preserveQuotedStrings', $content);
	
	// Here, even the comments are clean up of extra whitespaces
	//$pattern = '/("[^"\\\\]*(?:\\\\.[^"\\\\]*)*"|\'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\')|\s+/';	
	// $content = preg_replace_callback($pattern, 'preserveQuotedStrings', $content);
	
	// Even remove single-line comments (// ...)
	//$pattern = '/\/\/.*$/m';
	//$content = preg_replace($pattern, '', $content);

	// Even remove multi-line comments (/* ... */)
	//$pattern .= '|\/\*[\s\S]*?\*\/';
	//$content = preg_replace($pattern, '', $content);
	
	// Remove multiline whitespace
	//$pattern = '/^\s*$/m';
	//$content = preg_replace($pattern, '', $content);	
	// ##############################################################################
	//		Insert
	// ##############################################################################

    // PHPDoc the file head
	$pattern = '/<\?php\s*(.*?)(?=function|\?>)/s';
	$replacement = "<?php
/**
* Applicationname
*
* MyPHPWeb
* Applicationdescription
*
* @category Category
* @package  PackageName
* @author     Your Name
* @version    1.0.0
*/
\n\$1";
		$content = preg_replace($pattern, $replacement, $content);
	
	// PHPDoc the function
	$pattern = '/function\s+(\w+)\s*\((.*?)\)\s*{/s';
	// Use preg_replace_callback to apply the callback function to each function declaration
	$content = preg_replace_callback($pattern, 'generatePHPDoc', $content);

    // Define the desired brace and indentation format
    //$content = preg_replace('/\s*.=\s*/', " .= ", $content);
    //$content = preg_replace('/\s*.\s*/', " . ", $content);
    $content = preg_replace('/\s*{\s*/', " \n{\n", $content);
    $content = preg_replace('/\s*}\s*/', "\n}\n", $content);
    $content = preg_replace('/\s*;\s*/', ";\n", $content);

    // Check and correct indentation (assuming 4 spaces per level)
    $lines = explode("\n", $content);
    $indents = 0;
    $formattedLines = [];

    foreach ($lines as $line) {
        if (preg_match('/^\s*\}\s*$/', $line)) {
            $indents--;
        }

        $formattedLines[] = str_repeat('	', max(0, $indents)) . ltrim($line);

        if (preg_match('/^\s*{\s*$/', $line)) {
            $indents++;
        }
    }

    // Reconstruct the content with corrected indentation
    $content = implode("\n", $formattedLines);

    // Write the formatted content to the target file
    file_put_contents($target_filename, $content);
}
// Define a callback function to generate PHPDoc for each function
function generatePHPDoc($matches) 
{
	$newargumentsList = '';
    $functionName = $matches[1];
    $argumentsList = $matches[2];

    // Split the arguments list into individual arguments
    $arguments = explode(',', $argumentsList);
	

    // Create a PHPDoc block
    $phpDoc = "\n\n/**";
    $phpDoc .= "\n * Description of $functionName.";
    $phpDoc .= "\n*";
	    
    foreach ($arguments as $argument) {
        // Trim and clean up each argument
        $argument = trim($argument);
        
        if (!empty($argument)) {
            $phpDoc .= "\n* @param mixed $argument DescriptionOfargument.";
        }
    }
	
    $phpDoc .= "\n*\n * @return void This function return nothing.";
	$phpDoc .= "\n###############################################################################";
    $phpDoc .= "\n*/";

    // Replace the original function declaration with the PHPDoc block
    return  $phpDoc . "\nfunction $functionName($argumentsList) {";
}

// Define a callback function to preserve quoted strings
function preserveQuotedStrings($matches) 
{
    return $matches[1] ?? " ";
}
function replaceSingleLineComment($matches) 
{
    $comment = $matches[1];

    // Convert the single-line comment into a multiline comment
    return "/* $comment */";
}

// ##############################################################################
//		Do it
// ##############################################################################

formatPHPFile();
echo "PHP script formatted successfully.";






?>
