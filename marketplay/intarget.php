<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>PlayThread Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Cache-Control" content="no-cache" />
</head>
<body>
<?php

class xml2Array {
   
    var $arrOutput = array();
    var $resParser;
    var $strXmlData;
   
    function parse($strInputXML) {
   
            $this->resParser = xml_parser_create ();
            xml_set_object($this->resParser,$this);
            xml_set_element_handler($this->resParser, "tagOpen", "tagClosed");
           
            xml_set_character_data_handler($this->resParser, "tagData");
       
            $this->strXmlData = xml_parse($this->resParser,$strInputXML );
            if(!$this->strXmlData) {
               die(sprintf("XML error: %s at line %d",
            xml_error_string(xml_get_error_code($this->resParser)),
            xml_get_current_line_number($this->resParser)));
            }
                           
            xml_parser_free($this->resParser);
           
            return $this->arrOutput;
    }
    function tagOpen($parser, $name, $attrs) {
       $tag=array("name"=>$name,"attrs"=>$attrs);
       array_push($this->arrOutput,$tag);
    }
   
    function tagData($parser, $tagData) {
       if(trim($tagData)) {
            if(isset($this->arrOutput[count($this->arrOutput)-1]['tagData'])) {
                $this->arrOutput[count($this->arrOutput)-1]['tagData'] .= $tagData;
            }
            else {
                $this->arrOutput[count($this->arrOutput)-1]['tagData'] = $tagData;
            }
       }
    }
   
    function tagClosed($parser, $name) {
       $this->arrOutput[count($this->arrOutput)-2]['children'][] = $this->arrOutput[count($this->arrOutput)-1];
       array_pop($this->arrOutput);
    }
}


/***************** MAIN  **********************/

$pageurl='http://193.242.251.253:82/Info?userName=dkarDev&company=Intarget&IBSessionId=1234-5678&lang=GR&code=EXAE.ATH';
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_URL, $pageurl );
$contents = curl_exec ( $ch );
curl_close($ch); 
//print ($contents);
	
//$arrOutput = xml2array($pageurl);
//print_r($arrOutput);
$objXML = new xml2Array();
$arrOutput = $objXML->parse($contents);

 
 foreach($arrOutput as $val)
 {	
  foreach($val as $v1)
     if (is_array($v1))
     	foreach($v1 as $v2)
 		{	
		 if (is_array($v2))
    	 	{
			 foreach($v2 as $v3)
 	 	 	 {	
		 	  if (is_array($v3))
    	 		{	
				foreach($v3 as $col=>$v4)
 				{	
	     		if (is_array($v4))
					{
	  	 			foreach($v4 as $col5=>$v5)
					if (is_array($v5))
	  	 				foreach($v5 as $col6=>$v6)
	   		 				if ($v6=="instrStatusSysName")   // $v6 has the field name  //
								echo "<b>$v6</b>"."==>";
							else	
							    echo $v6."==>"; 
					else		
	   		 		 if ($col5!="name")
					      echo $v5;      // mb_convert_encoding($v5, "UTF-8","ISO-8859-1"); 
						
					echo "<br>";
					}		 	
				}						
			}			
		 }
		}
	}
 }
 
//print_r($arrOutput); 
?>
</body>
</html>