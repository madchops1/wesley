<?php
// THIS IS THE SLIDESHOW WIDGET

// SETTINGS AND SESSION
global $_SETTINGS;
global $_SESSION;

// DEFINE WIDGET'S VARS
$widgetName     = 'Slideshow';
$defaultWidth   = "200px";
$defaultHeight  = "100px";
$panelPath		= "/admin/widgets/slideshow/panel.php";
$id             = $widgetId;
$defaultImage	= "/admin/widgets/slideshow/_nivo-slider.not-used/demo/images/up.jpg";
$defaultImage2	= "/admin/widgets/slideshow/_nivo-slider.not-used/demo/images/walle.jpg";

// GET THE CURRENT MAIN WIDGET WIDGET DATA
$select =   "SELECT * FROM things ".
            "WHERE 1=1 ".
            "AND thing_id='".$id."' ".
            "AND active='1' ".
            "LIMIT 1";
$result = 	doQuery($select);

// IF THERE IS A WIDGET 
if($row = mysql_fetch_array($result))
{
    
    // GET SLIDES
    $selectSlides =     "SELECT * FROM things WHERE 1=1 ".
                        "AND parent_thing_id='".$id."' ".
                        "AND name='slide' ".
                        "AND active='1' ".
						"ORDER BY sort_order ASC, date DESC";
    $resultSlides   = doQuery($selectSlides);                    
    
    // SET DEFAULT SLIDES (THERE NEEDS TO BE AT LEAST 2 SLIDES)
    if(!mysql_num_rows($resultSlides))
    {
		// INSERT INITIAL DEFAULT SLIDE
        $insertSlide =  "INSERT INTO things SET ".
                        "parent_thing_id='".$id."',".
                        "name='slide',".
                        "text='Slide 1',".
                        "website_id='".$_SETTINGS['website_id']."',".
                        "url='',".
                        "file='".$defaultImage."'";
        doQuery($insertSlide);
		
		// INSERT SECOND DEFAULT SLIDE
		$insertSlide =  "INSERT INTO things SET ".
                        "parent_thing_id='".$id."',".
                        "name='slide',".
                        "text='Slide 2',".
                        "website_id='".$_SETTINGS['website_id']."',".
                        "url='',".
                        "file='".$defaultImage2."'";
		doQuery($insertSlide);
		
        $resultSlides = doQuery($selectSlides);
    }
    
    // TESTING
    //debugArray($resultSlides);
    
    // GET OR SET OTHER DEFAULT OPTIONS (DEFAULT THINGS)
    $thingsArray    = array();
    $thingsArray['effect']['text'] 				= "fade";
    $thingsArray['animation_speed']['text'] 	= "500";
    $thingsArray['pause_speed']['text'] 		= "3000";
	
	//$thingsArray['nav_position']['left'] 		= "0px";
	//$thingsArray['nav_position']['top'] 		= "0px";
	
    //var_dump($thingsArray);
    
    foreach($thingsArray as $key => $value)
    {
        //echo "NAME: ".$key."<br><br>"; 					// NAME
        foreach($value as $thingKey => $thingValue)
        {
            //echo "COLUMN: ".$thingKey."<br><br>"; 		// COLUMN
            //echo "VALUE: ".$thingValue."<br><br>"; 		// VALUE
            
            $checkThing = 	"SELECT * FROM things ".
                            "WHERE 1=1 ".
                            "AND parent_thing_id='".$id."' ".
                            "AND website_id='".$_SETTINGS['website_id']."' ".
                            "AND name='".$key."' ".
                            "AND active='1' ".
                            "LIMIT 1";
            $resultCheckThing = doQuery($checkThing);
            if(!mysql_num_rows($resultCheckThing)){				
                $insertThing = 	"INSERT INTO things SET ".
                                "parent_thing_id='".$id."',".
                                "website_id='".$_SETTINGS['website_id']."',".
                                "name='".$key."',".
                                "".$thingKey."='".$thingValue."',".
                                "active='1'";
                doQuery($insertThing);
            } else {
				$rowThing = mysql_fetch_array($resultCheckThing);
				$thingsArray[$key][$thingKey] = $rowThing[$thingKey];
			}
            //echo "<br><br>CHECK: ".$checkThing." <br> INSERT: ".$insertThing."<br><br>";
        }
    }

	//debugArray($thingsArray);
	
    // GET THE POSITION OF THE WIDGET TO PASS TO THE WIDGET HEADER (!IMPORTANT REQUIRED FOR EVERY WIDGET)
	if($row['width'] != "" AND $row['width'] != '0'){	$defaultWidth	= $row['width']."px"; }
	if($row['height'] != "" AND $row['height'] != '0'){	$defaultHeight	= $row['height']."px"; }
	$left			= $row['left'];
	$top			= $row['top'];
}

// (!IMPORTANT REQUIRED FOR EVERY WIDGET)
// IF SESSION CMS WIDGETS ARE ADDED TO PAGE VIA AJAX SO $content MUST BE CONCANTENATED WITH OTHER WIDGET DATA
// BUT IF USER IS VIEWING LIVE WEBSITE THEN $content IS INCLUDED VIA PHP EACH WIDGET IS INDIVIDUALLY INCLUDED SO CONTENT VAR MUST BE REFRESHED
if(sessionRequest('cms') == 1){ $content = ""; } 

// WIDGET HEADER
@$content .= widgetHeader($widgetName,$defaultWidth,$defaultHeight,$panelPath,$id,$top,$left);



// WIDGET


/** CYCLE SLIDER
************************************************************************************/

// HTML
$content .= "	<div class='slideshow".$id." slideshow-wrapper ".(sessionRequest('cms') == 1 ? " wesley-file-drop " : "")."' ".(sessionRequest('cms') == 1 ? " id='file-drop-".$id."' " : "").">";
$i=1;
while($slide = mysql_fetch_array($resultSlides))
{
	$content .= "   <a href='".($slide['url'] == '' ? "javascript:void(0);" : $slide['url'])."' thing_id='".$slide['thing_id']."' id='wesleyslide".$slide['thing_id']."' slide='".($i-1)."'>
						<img src='".$slide['file']."' />
						<span id='wesleyslidetext".$slide['thing_id']."'>".$slide['text']."</span>
					</a>";
	$i++;
}			
$content .= "	</div>";

if(sessionRequest('cms') == 1)
{
	$content .= "	<script type='text/javascript'>
	
						// DRAGABLE TEXT
						$('.slideshow".$id." a').children('span').draggable({
							stop: function(event, ui){
								var position = $(this).position();
								alert('Dragged! '+position.left+','+position.top+'');
							}
						});
	
						function onBefore(curr, next, opts) { 
        
							$('#file-drop-".$id."').droppable({
								accept: '.draggable-image',
								drop: function( event, ui ){
									var draggable = ui.draggable;
									var widgetThingId 		= $(this).closest('.wesley-cmsnav-widget').attr('id');
									var spotId				= '#' + $(this).closest('.wesley-spot').attr('id') + '';
									var filePath 			= draggable.attr('path');
									var slideText			= 'New Slide';
									var currentSlide 		= $(this).children('a:visible').attr('thing_id');
									var currentSlideText	= $(this).children('a:visible').children('span').html();
									
									//alert('Dropped! | WIDGET THING ID: ' + widgetThingId + ' | FILE PATH: ' + filePath + '');
									
									$.prompt('Replace slide ' + currentSlideText + ' or add a new slide.',{
										buttons:[
											{title: 'Replace Slide',value:'replace'},
											{title: 'New Slide',value:'new'}
											], 
										submit: function(v,m,f){ 
											//alert(v); 
											
											// REPLACE
											if(v == 'replace'){
												$.ajax({
													type:'POST',
													data:{ replaceSlide: currentSlide, filePath: filePath },
													url:'/admin/widgets/slideshow/panel.php',
													success:function(data){
														// data will return the new thing id
														//alert(data);
														//var jsonObj = $.parseJSON(data);
														// ADD THE NEW SLIDE TO THE SLIDESHOW
														//opts.addSlide('<a href=\'javascript:void(0);\' thing_id=\''+data+'\'><img src=\''+filePath+'\' /><span>'+slideText+'</span></a>'); 
														
														// REPLACE THE NEW SLIDE IMG
														$('a#wesleyslide'+currentSlide+'').children('img').attr('src',filePath);
														
													}
												});
											}
											
											// NEW
											if(v == 'new'){
												$.ajax({
													type:'POST',
													data:{ parentThingId: widgetThingId, newSlide: 1, filePath: filePath, slideText: slideText },
													url:'/admin/widgets/slideshow/panel.php',
													success:function(data){
														// data will return the new thing id
														//alert(data);
														//var jsonObj = $.parseJSON(data);
														
														// ADD THE NEW SLIDE TO THE SLIDESHOW
														opts.addSlide('<a href=\'javascript:void(0);\' thing_id=\''+data+'\'><img src=\''+filePath+'\' /><span>'+slideText+'</span></a>'); 
														$('.slideshow".$id."').cycle(opts.slideCount-1);
													}
												});
											}
										} 
									});
								}
							});
						}
					</script>";
}

// SLIDESHOW NAVIGATION
$content .= "	<div class='slideshow-nav".$id." slideshow-nav-wrapper'></div>";
				
// SLIDESHOW JAVASCRIPT
$content .= "	<script type='text/javascript'>";
	
	// LIVE SCRIPTS
	if(sessionRequest('cms') == 0)
	{
		// SLIDESHOW INITIALIZATION					
		$content .= "		$('.slideshow".$id."').cycle({
								fx: '".$thingsArray['effect']['text']."', 							// transition type
								speed: ".$thingsArray['animation_speed']['text'].", 				// speed
								timeout: ".$thingsArray['pause_speed']['text'].",					// pause
								pager:  '.slideshow-nav".$id."',									// pager
								pause: 1															// pause on hover
							});";
	}
	
	// EDITING SCRIPTS
	if(sessionRequest('cms') == 1)
	{
		// SLIDESHOW INITIALIZATION					
		$content .= "		$('.slideshow".$id."').cycle({
								fx: '".$thingsArray['effect']['text']."', 							// transition type
								speed:  ".$thingsArray['animation_speed']['text'].", 				// speed
								timeout: ".$thingsArray['pause_speed']['text'].", 					// pause
								pager:  '.slideshow-nav".$id."',									// pager
								pause: 1,															// pause on hover
								before: onBefore
							});";
		
		// STOP THE SLIDESHOW					
		$content .= "		$('.slideshow".$id."').cycle('pause');";
		
		// DEACTIVATE LINKS
		$content .= "		$('.slideshow".$id." a').live('click',function(e){ e.preventDefault(); });";
	}			
	
$content .= "	</script>";
          
// WIDGET FOOTER
@$content .= widgetFooter();

// IF SESSION CMS WIDGETS ARE ADDED TO PAGE VIA AJAX SO $content MUST BE ECHO'D
// BUT USER IS VIEWING LIVE WEBSITE THEN $content IS INCLUDED VIA PHP SO DO NOT ECHO
if(sessionRequest('cms') == 1){ echo $content; }
?>