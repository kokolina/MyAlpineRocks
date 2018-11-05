<?php
namespace Myalpinerocks;

abstract class BaseController
{
    public static function renderTemplate(string $templatePath, array $data = [])
    {
        if(!file_exists($templatePath)){
            throw new \Exception();
        }
        //Extracts vars to current view scope
        extract($data);

        //Starts output buffering
        ob_start();

        //Includes contents
        include $templatePath;
        $buffer = ob_get_contents();
        @ob_end_clean();

        //Returns output buffer
        return $buffer;            
    }
    
    public static function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = addslashes($data);
        return $data;
    }
 
}
