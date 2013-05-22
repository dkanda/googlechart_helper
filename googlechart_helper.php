<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Euphoria
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package     Euphoria
 * @author      Fabio Borraccetti
 * @copyright   Copyright (c) 2013 Fabio Borraccetti
 * @license     
 * @link        http://borraccetti.it
 * @since       Version 1.0
 * @filesource  https://github.com/fborraccetti/euphoria
 */

// ------------------------------------------------------------------------

/**
 * Euphoria Jquery plot Helpers
 *
 * @package     Euphoria
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Fabio Borraccetti
 * @link        https://github.com/fborraccetti/euphoria
 */
// ------------------------------------------------------------------------


function plot_cirque($how,$total=null,$color="94BA65",$label=""){
    if($color==''){
        
        /*$col=array(
        "94BA65",
        
        
        );*/
        
        $color="94BA65";
    }
    $html='<div class="cirque-stats">';
    $tothtml="";
    if(isset($total)and $total>0){
        $tothtml='data-total="'.$total.'"';
    }
    $data_label='';
    if(strlen($label)>0){
        $data_label='data-label="'.$label.'"';
    }
    $html.='<div class="ui-cirque" data-value="'.$how.'" '.$tothtml.' data-arc-color="#'.$color.'" '.$data_label.'"></div>';
    $html.='</div>';
    return $html;
}

function plot_line($title,$testa,$data_block,$xdata="" ,$ydata=""){
        if(is_object($testa))
            $testa=get_object_vars($testa);
        $ret = "";
        
        $div_id=url_title($title).uniqid();
        $html="<div id='".$div_id."' class='google-chart'></div>";
        
        $js='        <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = new google.visualization.DataTable();
      ';
        $js.=data_encode($testa,$data_block,$xdata);       
        /* //var view = google.visualization.arrayToDataTable(".$encodata."); */ 
        $js.="
        
        var options = {
          title: '".$title."',  
          legend : 'right' ,
          chartArea:{left:10,top:20,width:'80%',height:'80%'},
          vAxis: {minValue: 0, title: '".$ydata."',  titleTextStyle: {color: 'red'}},
          hAxis: {title: '".$xdata."', titleTextStyle: {color: 'red'}}
        };
        
        var chart = new google.visualization.LineChart(document.getElementById('".$div_id."'));
        chart.draw(data, options);
      }
    </script>";
        

        

        return $ret.$html.$js;            
}

function plot_pie($title,$testa,$data_block,$xdata="" ,$ydata=""){
        if(is_object($testa))
            $testa=get_object_vars($testa);
        $ret = "";
        
        $div_id=url_title($title).uniqid();
        $html="<div id='".$div_id."' class='google-chart'></div>";
        
        $js='        <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = new google.visualization.DataTable();
      ';
        $dat=null;
        $riga=0;
        $cols=0;
        $atd="";
        foreach($testa as $k=>$v){
            if($k==$xdata){
            	$js.="data.addColumn('string', '".$v."');\n";
            }else{
            	$js.="data.addColumn('number', '".$v."');\n";
            }	
        }
		
		
        $js .="data.addRows(".count($data_block).");\n"; 
        $riga=0;
        foreach($data_block as $chiave=>$d){
        	$x=0; 
			$js.="data.addRow([";
			
        	foreach($d as $k=>$v){ //{v:".$riga.",f:'".$v."'}
        		if($x!=0)
					$js.=",";
        		if($k==$xdata){
        		    $js.="'".$v."'";
        		}else{
        			$js.=" {v:".str_replace("€ ", "", $v).",f:'".$v."'}";
        		}
        		$x++;
        	} 
			$js.=" ]);\n";
        	$riga++;
        }      
        /* //var view = google.visualization.arrayToDataTable(".$encodata."); */ 
        $js.="
        
        var options = {
          title: '".$title."',  
          legend : 'right' ,
          vAxis: {minValue: 0, title: '".$ydata."',  titleTextStyle: {color: 'red'}},
          hAxis: {title: '".$xdata."', titleTextStyle: {color: 'red'}}
        };
        
        var chart = new google.visualization.PieChart(document.getElementById('".$div_id."'));
        chart.draw(data, options);
      }
    </script>";
        

        

        return $ret.$html.$js;            
}
function data_encode($testa,$data_block,$xdata){
	    $dat=null;
		$js="";
        $riga=0;
        $cols=0;
        $atd="";
		$colonna=array();
		//$js.="data.addColumn('number', '".$xdata."');\n";
		$js.="data.addColumn('string', '".$xdata."');\n";
        foreach($testa as $k=>$v){
            if($v!=$xdata){
            	$js .="data.addColumn('number', '".$v."');\n";
            }
        	$cols++;
        }

        //$js .="data.addRows(".count($data_block).");\n"; 
        $riga=0;
        foreach($data_block as $chiave=>$d){
        	
			$js.="data.addRow([";
			$x=0; 
			$colonna=array();
        	foreach($d as $k=>$v){ //{v:".$riga.",f:'".$v."'}
        		if($k==$xdata){
        		    //$colonna[$xdata]="{v:".$riga.",f:'".$v."'}";
        		    $colonna[$xdata]="{v:'".$v."',f:'".$v."'}";
        		}else{
        			$colonna[$k]=" {v:".str_replace(",",".",preg_replace("/[^0-9,.]/", "", $v)).",f:'".$v."'}";
        		}
        		$x++;
        	}
			if(isset($xdata) and isset($colonna[$xdata])) 
				$js.=$colonna[$xdata];
			foreach($colonna as $k=>$c){
				if($k!=$xdata){
					$js.=",";
					$js.=$c;
				}
			}				
			$js.=" ]);\n";
        	$riga++;
        }  
		return $js;
}

function plot_image_bar($title,$testa,$data_block,$xdata="" ,$ydata=""){
        if(is_object($testa))
            $testa=get_object_vars($testa);
        $ret = "";
        
        $div_id=url_title($title).uniqid();
        $html="<div id='".$div_id."' class='google-chart'></div>";
        
        $js='        <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = new google.visualization.DataTable();
      ';
		$js.=data_encode($testa,$data_block,$xdata);  
		
        /* //var view = google.visualization.arrayToDataTable(".$encodata."); */ 
        $js.="
        
	        var options = {
	          title: '".$title."',  
	          legend : 'bottom' ,
	          'chartArea': {'width': '80%', 'height': '80%'},
	          vAxis: {minValue: 0, title: '".$ydata."',  titleTextStyle: {color: 'red'}},
	          hAxis: {title: '".$xdata."', titleTextStyle: {color: 'red'}},
	          isStacked: true
	        };

        var chart = new google.visualization.BarChart(document.getElementById('".$div_id."'));
        chart.draw(data, options);
      }
    </script>";
        

        

        return $ret.$html.$js;            
}

function plot_vertical($title,$testa,$data_block,$xdata="" ,$ydata=""){
        if(is_object($testa))
            $testa=get_object_vars($testa);
        $ret = "";
        
        $div_id=url_title($title).uniqid();
        $html="<div id='".$div_id."' class='google-chart'></div>";
        
        $js='        <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = new google.visualization.DataTable();
      ';
		$js.=data_encode($testa,$data_block,$xdata);  
		
        /* //var view = google.visualization.arrayToDataTable(".$encodata."); */ 
        $js.="
        
        var options = {
          title: '".$title."',  
          legend : 'right' ,
          vAxis: {minValue: 0, title: '".$ydata."',  titleTextStyle: {color: 'red'}},
          //
          hAxis: {title: '".$xdata."', titleTextStyle: {color: 'red'}}
        };
        
        var chart = new google.visualization.ColumnChart(document.getElementById('".$div_id."'));
        chart.draw(data, options);
      }
    </script>";
        

        

        return $ret.$html.$js;            
}



function plot_table($title,$testa,$data_block,$xdata="" ,$ydata=""){
        if(is_object($testa))
            $testa=get_object_vars($testa);
        $ret = "";
        
        $div_id=url_title($title)."table";
        $html="<div id='".$div_id."' class='google-chart'></div>";
        
        $js='        <script type="text/javascript">
    google.load("visualization", "1", {packages:["table"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = new google.visualization.DataTable();
      ';
        $js.=data_encode($testa,$data_block,$xdata);        
        /* //var view = google.visualization.arrayToDataTable(".$encodata."); */ 
        $js.="        
        var chart = new google.visualization.Table(document.getElementById('".$div_id."'));
        chart.draw(data, null);
      }
    </script>";
        

        

        return $ret.$html.$js;            
}



