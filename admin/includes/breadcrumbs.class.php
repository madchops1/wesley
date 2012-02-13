<?
/*******************************************************************
*
* Karl Steltenpohl Development Framework
* Version 1.0
* Copyright 2009 Karl Steltenpohl Development All Rights Reserved
* Last Updated: December 05, 2009
* Created On: December 05, 2009
*
*******************************************************************/
/*************************
* Crumbline Class
*************************/
class Crumbline {/*{{{*/

	var $crumbline;
		
	function Crumbline() {
	
		$this->crumbline = array();
	}
	/*************************
	* Add to Crumb line
	*************************/
	function addCrumb($name, $link) {/*{{{*/
		
		$inArray = 0;
		for( $i=0; $i<count($this->crumbline); $i++ )
		{
			if( $this->crumbline[$i]['name'] == $name )
			{
				$inArray = 1;
			}
		}
		
		if( $inArray == 0 )
		{
			$c = count($this->crumbline);
			$this->crumbline[$c]['name'] = $name;
			$this->crumbline[$c]['link'] = $link;
		} else {
			$this->removeCrumb($name);
			$this->addCrumb($name, $link);
		}
		
	}/*}}}*/
	
	/*************************
	* Remove From Crumb line
	*************************/
	function removeCrumb($name) {/*{{{*/
		
		$inArray = 0;
		for( $i=0; $i<count($this->crumbline); $i++ )
		{
			if( $this->crumbline[$i]['name'] == $name )
			{
				$inArray = 1;
			}
		}
		
		if( $inArray == 1 )
		{
			array_pop($this->crumbline);
			$this->removeCrumb($name);
		}
	}/*}}}*/

	/*************************
	* Print Crumb line
	*************************/
	function printCrumbLine() {/*{{{*/
	
		for( $i=0; $i<count($this->crumbline); $i++ )
		{
			if( $i != 0 )
			{
				echo ' <span CLASS="bullet_link">&raquo;</span> ';
			}
			
			echo '<a href="' . $this->crumbline[$i]['link'] . '" CLASS="bullet_link">' . $this->crumbline[$i]['name'] . '</a>';
		}
	}/*}}}*/
	
	/*************************
	* Clear Crumb line
	*************************/
	function clearCrumbLine() {/*{{{*/
	
		$this->crumbline = array();
		
	}/*}}}*/

/*}}}*/}
?>