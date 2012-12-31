<?php
class Pagination 
{
	//Current Page
	var $page;
	
	//Records Per Page
	var $size;
	
	//Total Records
	var $total_records;
	
	//Link in Nav
	var $link;
	
	 /**********************************
	 * Class Constructor
	 **********************************/
	function Pagination($page = null, $size = null, $total_records = null)
	{
		$this->page = $page;
		$this->size = $size;
		$this->total_records = $total_records;
	}
	
	// SET Current Page
	function setPage($page)
	{
		$this->page = 0+$page;
	}
	
	// SET Records Per Page
	function setSize($size)
	{
		$this->size = 0+$size;
	}
		
	// SET TOTAL RECORDS
	function setTotalRecords($total)
	{
		$this->total_records = 0+$total;
	}
	
	// SET LINK
	function setLink($url)
	{
		$this->link = $url;
	}
	
	// RETURN LIMIT STATEMENT
	function getLimitSql()
	{
		$sql = "LIMIT " . $this->getLimit();
		return $sql;
	}
		
	// LIMIT STATEMENT
	function getLimit()
	{
		if ($this->total_records == 0)
		{
			$lastpage = 0;
		}
		else 
		{
			$lastpage = ceil($this->total_records/$this->size);
		}
		
		$page = $this->page;		
		
		if ($this->page < 1)
		{
			$page = 1;
		} 
		else if ($this->page > $lastpage && $lastpage > 0)
		{
			$page = $lastpage;
		}
		else 
		{
			$page = $this->page;
		}
		
		$sql = ($page - 1) * $this->size . "," . $this->size;
		
		return $sql;
	}
	
	// Navigation
	function create_links()
	{
		global $_SETTINGS;
		$totalItems = $this->total_records;
		$perPage = $this->size;
		$currentPage = $this->page;
		$link = $this->link;
		
		$totalPages = floor($totalItems / $perPage);
		$totalPages += ($totalItems % $perPage != 0) ? 1 : 0;

		if ($totalPages < 1 || $totalPages == 1){
			return '<div class="pagination">&nbsp;</div>';
		}

		$output = null;
		//$output = '<span id="total_page">Page (' . $currentPage . '/' . $totalPages . ')</span>&nbsp;';
				
		$loopStart = 1; 
		$loopEnd = $totalPages;

		if ($totalPages > 25)
		{
			if ($currentPage <= 23)
			{
				$loopStart = 1;
				$loopEnd = 25;
			}
			else if ($currentPage >= $totalPages - 12)
			{
				$loopStart = $totalPages - 18;
				$loopEnd = $totalPages;
			}
			else
			{
				$loopStart = $currentPage - 9;
				$loopEnd = $currentPage + 9;
			}
		}

		if ($loopStart != 1){
			$output .= sprintf('<li class="firstpage"><a href="' . $link . '"><img src="'.$_SETTINGS['website'].'admin/images/icons/left1_16.png" alt="next" border="0"> First</a></li></a></li>', '1');
		}
		
		if ($currentPage > 1){
			$output .= sprintf('<li class="previouspage"><a href="' . $link . '"><img src="'.$_SETTINGS['website'].'admin/images/icons/left_16.png" alt="next" border="0"> Previous</a></li></a></li>', $currentPage - 1);
		}
		
		for ($i = $loopStart; $i <= $loopEnd; $i++)
		{
			if ($i == $currentPage){
				$output .= '<li class="currentpage">' . $i . '</li> ';
			} else {
				$output .= sprintf('<li class="everyotherpage"><a href="' . $link . '">', $i) . $i . '</a></li> ';
			}
		}

		if ($currentPage < $totalPages){
			$output .= sprintf('<li class="nextpage"><a href="' . $link . '">Next <img src="'.$_SETTINGS['website'].'admin/images/icons/right_16.png" alt="next" border="0"></a></li>', $currentPage + 1);
		}
		
		if ($loopEnd != $totalPages){
			$output .= sprintf('<li class="lastpage"><a href="' . $link . '">Last <img src="'.$_SETTINGS['website'].'admin/images/icons/right1_16.png" alt="last" border="0"></a></li>', $totalPages);
		}

		return '<div class="pagination"><ul>' . $output . '</ul></div>';
	}
}

?>