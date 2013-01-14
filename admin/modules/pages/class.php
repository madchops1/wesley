<?php
/*************************************************************************************************************************************
*
*	Wesley (TM), A Karl Steltenpohl Development LLC Product and Service
*  	Copyright (c) 2011 Karl Steltenpohl Development LLC. All Rights Reserved.
*	
*	This file is part of Karl Steltenpohl Development LLC's WES (Website Enterprise Software).
*	Written By: Karl Steltenpohl
*	
*	Commercial License
*	http://wesley.wescms.com/license
*
*************************************************************************************************************************************/

// PAGES CLASS
class Pages {
	
	var $theme;
	
	// CONSTRUCTOR
	function Pages()
	{
		global $_SETTINGS;
		$this->theme = $_SETTINGS['theme'];
	}
	
	// CLEAN PAGE NAME FOR URL
	function formatCleanPageUrl($page)
	{
		$page = strtolower($page);
		$page = str_replace(" ","",$page);
		$page = ereg_replace("[^A-Za-z0-9]", "", $page);
		return $page;
	}
		
	// GET HOMEPAGE CLEAN URL NAME
	function homepageCleanUrl()
	{
		global $_SETTINGS;
		$homepageSelect			= "SELECT name FROM pages WHERE home='1' AND website_id='".settingRequest('website_id')."' LIMIT 1";
		$homepageResult			= doQuery($homepageSelect);
		$homepageRow			= mysql_fetch_array($homepageResult);
		$homepageName			= $this->formatCleanPageUrl($homepageRow['name']);			
		return $homepageName;
	}
	
	// GET HOMEPAGE ID
	function homepageId()
	{
		global $_SETTINGS;
		$homepageSelect			= "SELECT page_id FROM pages WHERE home='1' AND website_id='".settingRequest('website_id')."' LIMIT 1";
		$homepageResult			= doQuery($homepageSelect);
		$homepageRow			= mysql_fetch_array($homepageResult);
		$homepageId				= $this->formatCleanPageUrl($homepageRow['page_id']);			
		return $homepageId;
	}
	
	// GET ARRAY OF THEMES TEMPLATES
	function arrayTemplates()
	{
		global $_SETTINGS;
		$fileArray = array();
		$theme = $this->theme;
		$dir = $_SETTINGS['DOC_ROOT'].'themes/'.$theme.'';
		if(is_dir($dir))
		{
			if($handle = opendir($dir)) {
				while (false !== ($file = readdir($handle)))
				{
					if ($file != "." && $file != ".." AND strstr($file,"template_"))
					{
						array_push($fileArray,$file);	
					}
				}
				closedir($handle);
			}
		} else {
			wesleySystemError($_SETTINGS['DOC_ROOT'].'themes/'.$theme.' is not a directory.');
		}
		return $fileArray;
	}
	
	// GET ARRAY OF WIDGETS
	function arrayWidgets()
	{
		global $_SETTINGS;
		
		// GET NATIVE WIDGETS
		$nativeWidgets = arrayDirectories($_SETTINGS['DOC_ROOT'].'admin/widgets/');
		$newArray = array();
		foreach($nativeWidgets as $widget){
			$itemArray = array($widget,'/admin/widgets/'.$widget.'/');
			array_push($newArray,$itemArray);
		}
		$nativeWidgets = $newArray;
		
		// GET THEME WIDGETS
		$themeWidgets = arrayDirectories($_SETTINGS['DOC_ROOT'].'themes/'.$this->theme."widgets/");
		$newArray = array();
		foreach($themeWidgets as $widget){
			$itemArray = array($widget,'/themes/'.$this->theme."widgets/".$widget."/");
			array_push($newArray,$itemArray);
		}
		$themeWidgets = $newArray;
		
		$widgets = array_merge($nativeWidgets,$themeWidgets);
		return $widgets;
	}
	
	// GET CATEGORIES
	function getCategoryTree($rootid)
	{
		$arr = array();

		//$result = mysql_query("select * from PLD_CATEGORY where PARENT_ID='$rootid'");
		$select = "SELECT * FROM pages WHERE ";
		$result = doQuery($select);
		
		while($row = mysql_fetch_array($result))
		{ 
			$arr[] = array(
				"Title" => $row["Title"],
				"Children" => getCategoryTree($row["id"])
			);
		}
		return $arr;
	}

	// ATTRIBUTES STRING TO ARRAY - FOR CUSTOM TAGS
	function arrayAtrributes($attributes)
	{
		$attributes = str_replace('"','',$attributes);
		$attributes = str_replace("'","",$attributes);
		
		// TRIM UP THE ATTRIBUTES STRING
		$attributes = rtrim($attributes);
		$attributes = ltrim($attributes);
		
		// MATCH ATTRIBUTES
		//echo "<br>".$attributes."<br>";
		// EXPLODE ATTRIBUTES
		$attributesArray = explode(" ",$attributes);
		return $attributesArray;
	}

	// CMS EDITOR
	function cmsEditor()
	{
		global $_SETTINGS;
        
		// GET WIDGET LIST
		$widgetList = "	<div id='wesley-cmsnav-widgetmenu'>
							<div id='wesley-cmsnav-widgetstitle'>Widgets</div>
							<div id='wesley-cmsnav-widgetmenu-widgets'>";
		$widgets = $this->arrayWidgets();
		foreach($widgets as $widget)
		{
			$widgetList .= "	<a href='page.html' widget='".$widget[0]."' widgetpath='".$widget[1]."' page=".urlRequest('page')." >";
			$widgetList .= "		<img src='/admin/widgets/".$widget[0]."/icon.png' alt='".$widget[0]."' title='".$widget[0]."' />";
			$widgetList .= "		<span>".ucwords(str_replace("_"," ",$widget[0]))."</span>";
			$widgetList .= "	</a>";
			//$widgetList .= "<li>";
			//$widgetList .= "	<div class='wesley-draggable-widget'>";
			//$widgetList .= "		".$widget."";
			//$widgetList .- "	</div>";
			//$widgetList .= "</li>";
		}
		$widgetList .= "	</div>
						</div>";
		
        // GET CSS EDITOR
        $cssEditor = "  <div id='wesley-cmsnav-css-editor'>
                            <div id='wesley-cmsnav-css-editor-title'>CSS Editor <span id='wesley-css-element'></span></div>
                            <div id='wesley-css-editor'>";
        // GET THE CSS 
                            
        $cssEditor .= "     </div>
                        </div>";
                        
                        
                        
        // GET THE HTML
        //$htmlEditor = " <div>";
        //$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        //$htmlEditor .= $_SERVER['PATH_INFO'];
        //$url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        //$html = file_get_html("".$url."");
        //debugArray($html);
        //$htmlEditor.= " </div>";
        
        
        
        
        //$templateHtml   = $this->templateCallback("");
        //$indexHtml      = file_get_contents('themes_websites/'.$_SETTINGS['website_id'].'-'.$this->theme.'index.html');
        
        // BUILD THE UI
		$content = "";
		$content = "<div class='wesley-cmsnav' id='wesley-cmsnav'>
						<div class='wesley-cmsnav-toolbar'>
							<ul class='wesley-cmsnav-buttons wesley-cmsnav-leftbuttons'>
								<li><a href='#' class='wesley-cmsnav-logo wesley-cmsnav-button'>Wesley&trade;</a></li>
                                <li><a href='#' class='wesley-cmsnav-help wesley-cmsnav-button'>Help</a></li>
								<li><a href='#' class='wesley-cmsnav-pointer wesley-cmsnav-button'>Pointer</a></li>
								<!-- <li><a href='' class='wesley-cmsnav-button'>Button</a></li> -->
							</ul>
							<!-- CONTENT / DESIGN TABS -->
							<ul class='wesley-cmsnav-tabs'>
								<li><a href='#' id='wesley-cmsnav-content-tab'>Content</a></li>
								<li><a href='#' id='wesley-cmsnav-design-tab'>Design</a></li>
							</ul>
							<ul class='wesley-cmsnav-buttons wesley-cmsnav-rightbuttons' style='float:right;'>
								<li><a href='#' class='wesley-cmsnav-minimize wesley-cmsnav-button'>Minimize</a></li>
								<li><a href='#' class='wesley-cmsnav-maximize wesley-cmsnav-button'>Maximize</a></li>
								<li><a href='/".$this->getPage()."/cms/0' class='wesley-cmsnav-close'>Close</a></li>
							</ul>
							<div class='wesley-cmsnav-search' style='float:right;'>
								<a href='' id='wesley-cmsnav-switch-site'>Switch Website</a> 
                                <a href='' id='wesley-cmsnav-switch-page'>Switch Page</a>
                               
                                
                                <!-- <input value='search...'/> -->
                                
							</div>
							
						</div>
						<div class='wesley-cmsnav-inner'>
							<!-- CONTENT TAB -->
							<div id='wesley-cmsnav-content'>
								<div id='wesley-cmsnav-contenteditarea'>
								</div>
								".$widgetList."
							</div>
							<!-- DESIGN TAB -->
							<div id='wesley-cmsnav-design'>
								<div id='wesley-cmsnav-designeditarea'>
                                    <div class='wesley-cmsnav-contenteditareatitle'>
                                        <span>File</span>
                                        <div style='display:none;'>
                                            <ul>
                                                <li><a href=''>Save</a></li>
                                            </ul>
                                        </div>
                                        <ul class='wesley-cmsnav-tabs'>
                                            <li><a id='tab-html' href=''>Html</a></li>
                                        </ul>
                                        <ul style='float:right; margin:-5px 0 0 0' class='wesley-cmsnav-buttons wesley-cmsnav-rightbuttons'>
                                            <li><a class='wesley-design-theme wesley-cmsnav-button' href='#'>Theme</a></li>
                                            <li><a class='wesley-design-fullscreen wesley-cmsnav-button' href='#'>Fullscreen</a></li>
                                        </ul>
                                    </div>
                                    <textarea id='wesley-html-editor-html' style='display:none;'>
                                    </textarea>
								</div>
                                ".$cssEditor."
							</div>
							
							<!-- FIELDS -->
							<input type='hidden' id='wesley-field-page' value='".$this->getPage()."' />
							<!--
							<form>
								<label>Pages</label>
								<select id='pages' name='pages'></select> 
								<button>New Page</button>
								
								&nbsp;&nbsp;
								
								<label>Articles</label>
								<select id='articles' name='articles'></select>
								<button>New Article</button>
								
								&nbsp;&nbsp;
								Current Site: ".urlRequest('website')."
								Current Page: ".urlRequest('page')."
								
								
								<a href=''>Go to admin</a>
							</form>
							-->
						</div>
					</div>";
		return $content;
	}
	
	// GET PAGE
	function getPage()
	{
		$page = urlRequest('page'); 								// GET CURRENT PAGE
		if($page == ''){ $page = $this->homepageCleanUrl(); }		// IF THERE IS NO PAGE REQUEST GO TO HOMEPAGE
		return $page;
	}
	
	// <$mainNav /$> TAG CALLBACK
	function mainNavCallback($matches)
	{
		global $_SETTINGS;
		
		// debugArray($matches); // TESTING
		// DEFINE ATTRIBUTES FOR <$mainNav id='' class='' home='' /$>
		$id 	= '';
		$class	= '';
		$home	= '';
		
		// REMOVE SINGLE AND DOUBLE QUOTES FROM ATTRIBUTES STRING TO SIMPLIFY REGEX
		$attributes = $matches[1];
		$attributesArray = $this->arrayAtrributes($attributes);
		
		
		foreach($attributesArray as $attribute)
		{
			// EXPLODE THE SINGLE ATTRIBUTE NAME VALUE PAIR
			$singleAttributeArray = explode("=",$attribute);
			if($singleAttributeArray[0] == 'id'){ $id=$singleAttributeArray[1]; }
			if($singleAttributeArray[0] == 'home'){ $home=$singleAttributeArray[1]; }
			if($singleAttributeArray[0] == 'class'){ $class=$singleAttributeArray[1]; }
		}
		
		
		//$id = preg_match('/id=(.*) /i',$attributes);
		
		//debugArray($id);
		
		$content = "";
		$content .= "<ul id='".$id."' class='".$class."'>";
		
		// IF HOME
		if($home=='1' || $home=='true')
		{
			$content .= "	<li><a href='/".$this->homepageCleanUrl()."' id='home' class='nav' >Home</a></li>";
		}
		
		// GET TOP LEVEL PAGES
		$select = 	"SELECT * FROM pages a ".
					"LEFT JOIN pages_parents b ON a.page_id=b.page_id ".
					"WHERE 1=1 ".
					"AND b.parent_page_id='".$this->homepageId()."' ".
					"AND a.active='1' ".
					"AND a.status='Published' ".
					"AND a.website_id='".$_SETTINGS['website_id']."'";
		$result = doQuery($select);
		while($row = mysql_fetch_array($result))
		{
			$content .= "<li><a href='' id='' class=''>".$row['name']."</a></li>";
		}
		
		if(sessionRequest('cms') == 1)
		{
			$content .= "<li><a href=''></a></li>";
		}
		
		
		
		
		$content .= "</ul>";
		return $content;
	
	}
	
	// <$template /$> TAG CALLBACK
	function templateCallback($matches)
	{
		global $_SETTINGS;
		
		$page = $this->getPage();
		
		// GET PAGE TEMPLATE
		$pageSelect = 	"SELECT LOWER(REPLACE(name,' ','')) AS name,template FROM pages ".
						"WHERE ".
						"website_id='".$_SETTINGS['website_id']."' AND ".
						"active='1' AND status='Published'";
		$pageResult = doQuery($pageSelect);
		$template = "";
		while($pageRow = mysql_fetch_array($pageResult))
		{
			//var_dump($pageRow);
			//echo "<br>".$page." = ".$pageRow['name']."<br>";
			if($page == $pageRow['name'])
			{
				// GET THE CURRENT PAGE'S TEMPLATE
				$template = $pageRow['template'];
			}
		}
		
		$content = "";
		
		// ADD TEMPLATE TO CONTENT
		if(is_file('themes_websites/'.$_SETTINGS['website_id'].'-'.$this->theme.$template))
		{
			$content .= file_get_contents('themes_websites/'.$_SETTINGS['website_id'].'-'.$this->theme.$template);
			//$content .= "<br>----------<br>INCLUDING themes/".$this->theme.$template."<br>----------<br>";
		} else {
			wesleySystemError('File themes_websites/'.$_SETTINGS['website_id'].'-'.$this->theme.$template.' does not exist!');
		}
		
		return $content;
	}
	
	// <$themeRoot /$> TAG CALLBACK
	function themeRootCallback()
	{
        global $_SETTINGS;  
		$content = "";
		$content = 'http://wescms.com/themes_websites/'.$_SETTINGS['website_id'].'-'.$this->theme.'';
		return $content;
	}
	
	// <$title /$> TAG CALLBACK
	function titleCallback()
	{
		$content = "";
		return $content;
	}
	
	// <$customCss /$> TAG CALLBACK
	function customCssCallback()
	{
		$content = "";
		return $content;
	}
	
	// <$customJavascript /$> TAG CALLBACK
	function customJavascriptCallback()
	{
		$content = "";
		return $content;
	}
	
	// <$spot /$> TAG CALLBACK | WIDGETS GO INTO SPOTS
	function spotCallback($matches)
	{
		global $_SESSION;
		global $_SETTINGS;
		
		// DEFINE POSSIBLE SPOT ATTRIBUTES
		$name 		= "";
		$everypage 	= "";
		
		$attributes = $matches[1];
		$attributesArray = $this->arrayAtrributes($attributes);
		foreach($attributesArray as $attribute)
		{
			$singleAttributeArray = explode("=",$attribute);
			if($singleAttributeArray[0]=='name'){ $name=$singleAttributeArray[1]; }
			if($singleAttributeArray[0]=='everypage'){ $everypage=$singleAttributeArray[1]; }
		}
		
		// BUILD SPOT
		$content = "<div id='wesley-spot-".$name."' class='wesley-spot ".($everypage != '' ? "everypage" : "")."'>";
		
		// IF NOT EDITING THEN INCLUDE WIDGETS IN SPOTS VIA PHP INCLUDE - IF EDITING THEN WIDGETS INSERTED THROUGH AJAX
		if(sessionRequest('cms') != 1)
		{
			// GET WIDGETS VIA INCLUDE
			$select = 	"SELECT * FROM things ".
						"WHERE 1=1 ".
						"AND parent_thing_id = '0' ".
						"AND spot = 'wesley-spot-".$name."' ".
						"AND page = '".($everypage != "" ? "everypage" : $this->getPage())."' ".
						"AND widget != '' ".
						"AND active = '1' ".
						"AND website_id = '".$_SETTINGS['website_id']."' ".
						"ORDER BY thing_id DESC";
			//echo $select;
					
			$widgetresult = doQuery($select);
			$i = 0;
			while($row = mysql_fetch_array($widgetresult))
			{
				//$content .= $i;
				
				//flush();
				//ob_start();
				$widgetId = $row['thing_id'];
				//include('file.php');
				include $_SETTINGS['DOC_ROOT'].$row['widgetpath'].'index.php';
				//$contents = ob_get_clean();
				//$content .= $contents; 
				$i++;
			}
		}
		$content .= "</div>";
		
		return $content;
	}
	
	/*
	function workspaceCallback($matches)
	{
	  global $_SESSION;
	  global $_SETTINGS;
	
	  // DEFINE POSSIBLE SPOT ATTRIBUTES
	  $name 		= "";
	  $everypage 	= "";
	
	  $attributes = $matches[1];
	  $attributesArray = $this->arrayAtrributes($attributes);
	  foreach($attributesArray as $attribute)
	  {
	    $singleAttributeArray = explode("=",$attribute);
	    if($singleAttributeArray[0]=='name'){ $name=$singleAttributeArray[1]; }
	    if($singleAttributeArray[0]=='everypage'){ $everypage=$singleAttributeArray[1]; }
	  }
	
	  // BUILD SPOT
	  $content = "<div id='wesley-spot-".$name."' class='wesley-spot ".($everypage != '' ? "everypage" : "")."'>";
	
	  // IF NOT EDITING THEN INCLUDE WIDGETS IN SPOTS VIA PHP INCLUDE - IF EDITING THEN WIDGETS INSERTED THROUGH AJAX
	  if(sessionRequest('cms') != 1)
	  {
	    // GET WIDGETS VIA INCLUDE
	    $select = 	"SELECT * FROM things ".
	        "WHERE 1=1 ".
	        "AND parent_thing_id = '0' ".
	        "AND spot = 'wesley-spot-".$name."' ".
	        "AND page = '".($everypage != "" ? "everypage" : $this->getPage())."' ".
	        "AND widget != '' ".
	        "AND active = '1' ".
	        "AND website_id = '".$_SETTINGS['website_id']."' ".
	        "ORDER BY thing_id DESC";
	    //echo $select;
	    	
	    $widgetresult = doQuery($select);
	    $i = 0;
	    while($row = mysql_fetch_array($widgetresult))
	    {
	      //$content .= $i;
	
	      //flush();
	      //ob_start();
	      $widgetId = $row['thing_id'];
	      //include('file.php');
	      include $_SETTINGS['DOC_ROOT'].$row['widgetpath'].'index.php';
	      //$contents = ob_get_clean();
	      //$content .= $contents;
	      $i++;
	    }
	  }
	  $content .= "</div>";
	
	  return $content;
	}
	*/
	
	// CONSTRUCT PAGE
	function constructPage()
	{
		global $_GET;
		global $_POST;
		global $_SESSION;
		global $_SETTINGS;
		global $_SERVER;
				
		$cmsEditorScript 	= 	"";	// SCRIPTS
		$cmsEditorNav		=	""; // THE WEBSITE EDITOR
		$content 			= 	""; // WEBSITE CONTENT
		
		// DETECT IF ADMIN USER IS EDITING
		if(urlRequest('cms') == 1)
		{
			// IF LOGGED IN
			if($_SESSION['session']->admin->user_id != "")
			{
				$_SESSION['cms'] = 1;
			}
		}
		// ELSE ADMIN USER IS NOT EDITING
		//elseif(urlRequest('cms') == 0)
		//{
		//	$_SESSION['cms'] = 0;
		//}
				
		// IF CMS EDITING ADD MANDATORY CSS & JS FOR THE CMS EDITOR
		if(sessionRequest('cms') == 1)
		{
			$cmsEditorScript = "    <!-- <script type='text/javascript' src='http://wescms.com/admin/scripts/jquery/jquery-1.6.4.min.js'></script> -->
                                    <script type='text/javascript' src='http://wescms.com/admin/scripts/jquery/jquery-1.7.1.min.js'></script>
									<script type='text/javascript' src='http://wescms.com/admin/scripts/jquery-ui-1.8.16.custom/js/jquery-ui-1.8.16.custom.min.js'></script>
									<script type='text/javascript' src='http://wescms.com/admin/scripts/jquery-ui-1.8.16.custom/development-bundle/ui/jquery.ui.resizable.js'></script>
									<script type='text/javascript' src='http://wescms.com/admin/scripts/jquery-ui-1.8.16.custom/development-bundle/external/jquery.cookie.js'></script>
									
									<!-- DESKTOP TO BROWSER FILE UPLOAD DRAG AND DROP
									<script type='text/javascript' src='http://wescms.com/admin/scripts/blueimp-jQuery-File-Upload/jquery.iframe-transport.js'></script>
									<script type='text/javascript' src='http://wescms.com/admin/scripts/blueimp-jQuery-File-Upload/jquery.fileupload.js'></script>
									<script type='text/javascript' src='http://wescms.com/admin/scripts/blueimp-jQuery-File-Upload/jquery.fileupload-ui.js'></script>
									-->
									
									<!-- jQuery UPLOADIFY -->
									<link href='http://wescms.com/admin/scripts/uploadify-v2.1.4-1/uploadify.css' type='text/css' rel='stylesheet' />
									<script type='text/javascript' src='http://wescms.com/admin/scripts/uploadify-v2.1.4-1/swfobject.js'></script>
									<script type='text/javascript' src='http://wescms.com/admin/scripts/uploadify-v2.1.4-1/jquery.uploadify.v2.1.4.min.js'></script>
									
									<!-- IMPROMPTU (PROMPT PLUGIN) -->
									<script type='text/javascript' src='http://wescms.com/admin/scripts/jquery-impromptu.3.2.min.js'></script>
									
                                    <!-- JSBEAUTIFIER -->
                                    <script src='http://wescms.com/admin/scripts/einars-js-beautify-26c264d/beautify.js'></script>
                                    <script src='http://wescms.com/admin/scripts/einars-js-beautify-26c264d/beautify-html.js'></script>
                                    
                                    <!-- SCROLL TO -->
                                    <script src='http://wescms.com/admin/scripts/jquery.scrollTo-1.4.2/jquery.scrollTo-min.js'></script>
                                    
                                    <!-- CODE MIRROR -->
                                    <script src='http://wescms.com/admin/scripts/CodeMirror-2.18/lib/codemirror.js'></script>
                                    <script src='http://wescms.com/admin/scripts/CodeMirror-2.18/mode/javascript/javascript.js'></script>
                                    <script src='http://wescms.com/admin/scripts/CodeMirror-2.18/mode/xml/xml.js'></script>
                                    <script src='http://wescms.com/admin/scripts/CodeMirror-2.18/mode/css/css.js'></script>
									<script src='http://wescms.com/admin/scripts/CodeMirror-2.18/mode/htmlmixed/htmlmixed.js'></script>
                                    <link rel='stylesheet' href='http://wescms.com/admin/scripts/CodeMirror-2.18/lib/codemirror.css'>
                                    <link rel='stylesheet' href='http://wescms.com/admin/scripts/CodeMirror-2.18/theme/default.css'>
                                    <!--
                                    <script src='http://wescms.com/admin/scripts/CodeMirror-2.18/lib/util/simple-hint.js'></script>
                                    <link rel='stylesheet' href='http://wescms.com/admin/scripts/CodeMirror-2.18/lib/util/simple-hint.css'>
                                    <script src='http://wescms.com/admin/scripts/CodeMirror-2.18/lib/util/javascript-hint.js'></script>
                                    <link rel='stylesheet' href='http://wescms.com/admin/scripts/CodeMirror-2.18/theme/neat.css'>
                                    <script src='http://wescms.com/admin/scripts/CodeMirror-2.18/mode/scheme/scheme.js'></script>
                                    -->
									
                                    <!-- LIVEQUERY
                                    <script type='text/javascript' src='http://wescms.com/admin/scripts/livequery/jquery.livequery.js'></script>
                                    -->
                                     
                                    <!-- ckEDITOR -->
                                    <script type='text/javascript' src='http://wescms.com/admin/scripts/ckeditor/ckeditor.js'></script>
                                    <script type='text/javascript' src='http://wescms.com/admin/scripts/ckeditor/adapters/jquery.js'></script>
                                    <!-- <script src='http://wescms.com/admin/scripts/ckeditor/_samples/sample.js' type='text/javascript'></script> -->
                                    
									<!-- PAGES FRONTEND SCRTIPT -->
									<script type='text/javascript' src='http://wescms.com/admin/modules/pages/scripts/frontend_script.js'></script>
                                    
									<!-- STYLES -->
									<link rel='stylesheet' type='text/css' href='http://wescms.com/admin/scripts/jquery-ui-1.8.16.custom/css/custom-theme/jquery-ui-1.8.16.custom.css' media='screen, projection, tv' />
									<link rel='stylesheet' type='text/css' href='http://wescms.com/admin/modules/pages/styles/frontend_styles.css' />";
			$cmsEditorNav = $this->cmsEditor();
		}
		// LIVE WEBSITE MANDATORY CSS & JS
		else
		{
			$cmsEditorScript = "	<link rel='stylesheet' type='text/css' href='http://wescms.com/admin/modules/pages/styles/frontend_live_styles.css' />";
		}
		
		// CYCLE PLUGIN - CURRENTLY USED FOR SLIDESHOW WIDGET
		$cmsEditorScript .= "		<script type='text/javascript' src='http://wescms.com/admin/widgets/slideshow/scripts/jquery.cycle.all.js'></script>";
			
		// GET THE THEME INDEX
        /*
		if(is_file('themes/'.$this->theme.'index.html'))
		{
			$content .= file_get_contents('themes/'.$this->theme.'index.html');
		} else {
			wesleySystemError('File themes/'.$this->theme.'index.php does not exist... dude!');
		}
        */
        
		
        // NEW GET THE INDEX FOR THEME EDITING AND CUSTOM THEMES
        // IF THERE IS NO CUSTOM FOLDER FOR THIS THEME THEN CREATE IT
		if(!is_file('themes_websites/'.$_SETTINGS['website_id'].'-'.$this->theme.'index.html'))
        {
            // COPY FROM THE THEMES FOLDER
            $src = 'themes/'.$this->theme.'';
            $dst = 'themes_websites/'.$_SETTINGS['website_id'].'-'.$this->theme.'';
            recurse_copy($src,$dst);
        }
        
        // GET THE THEME INDEX
		if(is_file('themes_websites/'.$_SETTINGS['website_id'].'-'.$this->theme.'index.html'))
		{
			$content .= file_get_contents('themes_websites/'.$_SETTINGS['website_id'].'-'.$this->theme.'index.html');
		} else {
			wesleySystemError('File themes_websites/'.$_SETTINGS['website_id'].'-'.$this->theme.'index.html does not exist...');
		}
        
		
		//
		// WES TAG REPLACE		
		//
		
		/**
		 *  <$template /$>
		 *  this is no longer used since I have moved beyond the idea
		 *  of using templates. It seems so physical so in the box...
		 */
		//$content = preg_replace_callback('/<\$template(.*)\/\$>/',array(get_class($this), 'templateCallback'),$content); 					// REPLACE TEMPLATE FIRST
		
		// <$themeRoot /$>
		$content = preg_replace_callback('/<\$themeRoot(.*)\/\$>/',array(get_class($this), 'themeRootCallback'),$content);					// REPLACE THEME ROOT
		
		// <$mainNav /$>
		$content = preg_replace_callback('/<\$mainNav(.*)\/\$>/',array(get_class($this), 'mainNavCallback'),$content);						// REPLACE MAIN NAV
		
		// <$title /$>
		//$content = preg_replace_callback('/<\$title(.*)\/\$>/',array(get_class($this), 'titleCallback'),$content);						// REPLACE TITLE
		
		// <$customCSS /$>
		$content = preg_replace_callback('/<\$customCss(.*)\/\$>/',array(get_class($this), 'customCssCallback'),$content);					// REPLACE CUSTOM CSS
		
		// <$customJavascript /$>
		$content = preg_replace_callback('/<\$customJavascript(.*)\/\$>/',array(get_class($this), 'customJavascriptCallback'),$content);	// REPLACE CUSTOM JS
		
		// <$spot /$>
		$content = preg_replace_callback('/<\$spot(.*)\/\$>/',array(get_class($this), 'spotCallback'),$content);							// REPLACE SPOT
		
		/**
		 * <$workspace /$>
		 */
		//$content = preg_replace_callback('/<\$workspace(.*)\/\$>/',array(get_class($this), 'workspaceCallback'),$content);					// REPLACE WORKSPACE
				
		/**
		 * ADD THE CMS EDITOR AND REQUIRED CSS, JS
		 */
		$content = str_replace('</head>',$cmsEditorScript.' </head>',$content);		// SCRIPTS GO AT END OF <HEADER>
		$content = str_replace('</body>',$cmsEditorNav.' </body>',$content);		// EDITOR GOES AT END OF <BODY>
		
		// SPIT IT OUT
		echo $content;
	}	
	
	// GET PAGE TITLE
	function PageTitle()
	{
		global $_SETTINGS;
		global $_REQUEST;
		
		$page = $_REQUEST['page'];
		if($page == ""){ $page = $this->activeHomepage(); }
		
		$select = 	"SELECT * FROM pages WHERE ".
					"clean_url_name='".$page."' ".
					"".$_SETTINGS['demosqland']."";
					
		$result = mysql_query($select) or die("err");
		$row = mysql_fetch_array($result);
		
		$prefix = $_SETTINGS['titlePrefix'];
		$suffix = $_SETTINGS['titleSuffix'];
		$default = $_SETTINGS['titleDefault'];
		if($default != "")
		{
			return $default;
		}
		else
		{
			return "$prefix".$row['title']."$suffix";			
		}
	}
	
	// GET PAGE NAME
	function PageName()
	{
		global $_SETTINGS;
		global $_REQUEST;
		
		$page = $_REQUEST['page'];
		if($page == ""){ $page = $this->activeHomepage(); }
		
		$select = 	"SELECT * FROM pages WHERE ".
					"clean_url_name='".$page."' ".
					"".$_SETTINGS['demosqland']."";
		
		$result = mysql_query($select) or die("err");
		$row = mysql_fetch_array($result);
		return $row['name'];			
	}
	
	// GET PAGE NAME
	function PageSubTitle()
	{
		global $_SETTINGS;
		global $_REQUEST;
		
		$page = $_REQUEST['page'];
		if($page == ""){ $page = $this->activeHomepage(); }
		
		$select = 	"SELECT * FROM pages WHERE ".
					"clean_url_name='".$page."' ".
					"".$_SETTINGS['demosqland']."";
		
		$result = mysql_query($select) or die("err");
		$row = mysql_fetch_array($result);
		return $row['subtitle'];			
	}
	
	function PageImage()
	{
		global $_SETTINGS;
		global $_REQUEST;
		
		$page = $_REQUEST['page'];
		if($page == ""){ $page = $this->activeHomepage(); }
		
		$select = 	"SELECT * FROM pages WHERE ".
					"clean_url_name='".$page."' ".
					"".$_SETTINGS['demosqland']."";
		
		$result = mysql_query($select) or die("err");
		$row = mysql_fetch_array($result);
		return $row['image'];	
	}
	
	// HEAD TAG SEO Optimization 
	function headOptimization()
	{
		
		echo "<meta name='description' content='".$this->PageDescription()."' />";
		echo "<meta name='keywords' content='".$this->PageKeywords()."' />";
		echo "<title>".$this->PageTitle()."</title>";
	
	}
	
	// GET PAGE DESCRIPTION
	function PageDescription()
	{
		global $_SETTINGS;
		global $_REQUEST;
		
		$page = $_REQUEST['page'];
		if($page == ""){ $page = $this->activeHomepage(); }
		
		$select = 	"SELECT * FROM pages WHERE ".
					"clean_url_name='".$page."' ".
					"".$_SETTINGS['demosqland']."";
		
		$result = mysql_query($select) or die("err");
		$row = mysql_fetch_array($result);
		return $row['description'];			
	}
	
	// Get Page Keywords
	function PageKeywords()
	{
		global $_SETTINGS;
		global $_REQUEST;
		
		$page = $_REQUEST['page'];
		if($page == ""){ $page = $this->activeHomepage(); }
		
		$select = 	"SELECT * FROM pages WHERE ".
					"clean_url_name='".$page."' ".
					"".$_SETTINGS['demosqland']."";
		
		$result = mysql_query($select) or die("err");
		$row = mysql_fetch_array($result);
		return $row['keywords'];			
	}	
}
?>