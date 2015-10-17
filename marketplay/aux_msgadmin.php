<?php

class aux_msgadmin extends aux_basic
{
 function getMsgColumns($obj, $socket_id, $channel_id, $_DATA)
 {
 $dp=$this->dp;
 $script="";
 $invalid_chars = array(' ','-','/','%',"'");

 $table = $_DATA['objId'];

 $sort="";
 $types="";
 $columns="";
 $cols_align='';
 $colIds='';
 $AllcolumnsDef="";
 $AllcolumnsIds="";

 $q= "SELECT field,decimals,length,header,show_title,show_id from imk_msg_fields WHERE msg_id=\"$table\" and header>0 ORDER by show_order";
 $dp->setQuery($q);
 $rows = $dp->loadObjectList();
	
	$i=0;
	foreach($rows as $row){
		if ($row->header==3)
		{
		 if ($columns!=""){
		    $columns.= ",";
			$columnsDef.= ","; 
			$columnsIds.= ","; 
			}
			else{
					$columns="Time";
					$columnsDef='{key:"OASIS_TIME", label:"Time", sortable:true},';
					$columnsDef.='{key:"dd", hidden:true},';
					$columnsIds='"OASIS_TIME",';
					$columnsIds.='"dd",';
			}
		  } 
		  
		   {
		  		 if ($AllcolumnsDef!=""){
					$AllcolumnsDef.= ","; 
					$AllcolumnsIds.= ","; 
					}
				  else{
						$AllcolumnsDef='{key:"OASIS_TIME", label:"Time", sortable:true},';
						$AllcolumnsIds='"OASIS_TIME",';
						}
		   		}
				
	   if ($row->header==3)
	   {
	    $columns.= $row->field;
		$columnsDef.='{key:"'.($row->show_id!="" ?$row->show_id :str_replace($invalid_chars, "_", strtoupper($row->field))).'", label:"'.$row->show_title .'",'.'resizeable:true'.'}';
		$columnsIds.='"'.($row->show_id!="" ?$row->show_id :str_replace($invalid_chars, "_", strtoupper($row->field))).'"';
		}
		
		 {
			$AllcolumnsDef.='{key:"'.str_replace($invalid_chars, "_", strtoupper($row->field)).'", label:"'.$row->show_title .'",'.'resizeable:true'.",sortable:true".'}';
			$AllcolumnsIds.='"'.str_replace($invalid_chars, "_", strtoupper($row->field)).'"';
		}

		$i++;
	}
 $script = $table."columnsDef=[".$AllcolumnsDef."];";
 $script .= "columnsDef=[".$columnsDef."];";
 $script .= "columnsIds=[".$columnsIds."];";
 $script .= $table."columnsIds=[".$AllcolumnsIds."];";
 $script .= "cols=\"$columns\";";
 
 $obj->write($socket_id, $channel_id, $script);
 }
 
 } // class
?>
