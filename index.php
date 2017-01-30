<?php
date_default_timezone_set('America/Jamaica');
define('IN_WINDOWS',TRUE);
define('PBD',TRUE);
define('PATH', str_replace('\\','/',__dir__).'/');
$readme = null;
$note_name = 'note.txt';
$uniqueNotePerIP = false;

if($uniqueNotePerIP){
	// Use the user's IP as the name of the note.
	// This is useful when you have many people
	// using the app simultaneously.
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$note_name = PATH. md5($_SERVER['HTTP_X_FORWARDED_FOR']).'.txt';
	} else{
		$note_name = PATH. md5($_SERVER['REMOTE_ADDR']).'.txt';
	}
}

if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
	// This is an AJAX request
	if(isset($_POST['note'])){
		// Write the file to disk
		file_put_contents($note_name, $_POST['note']);
		echo '{"saved":1}';
	}
	exit;
}
$note_content = '

                Write your note here.

             It will be saved with AJAX.';

if( file_exists($note_name) ){
	$note_content = htmlspecialchars( file_get_contents($note_name) );
}
if( file_exists('README.md') ) $readme = htmlspecialchars(file_get_contents('README.md'));
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta http-equiv="Content-Language" content="en-ja" />
        <meta http-equiv="Content-Script-Type" content="text/javascript" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
    	<meta charset="utf-8" />
		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta http-equiv="CACHE-CONTROL" content="NO-CACHE" />-->
		<meta name="robots" content="index, follow" />
		<title>PBD jQuery Tabs - DEV</title>
		<meta name="title" content="PBD jQuery Tabs" />
		<meta name="author" content="Amalkong" />
		<meta name="keywords" content="jQuery,PBD,tabs" />
		<meta name="copyright" content="PBD © 2016" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width; initial-scale=1.0" />
		<meta name="Resource-type" content="Document" />

	    <link rel="stylesheet" type="text/css" href="css/jquery.fullPage.css" />
	    <link rel="stylesheet" type="text/css" href="css/examples.css" />
		<link rel="stylesheet" href="css/jquery-pbdTabs.css" />
		<style>

	/* Style for our header texts
	* --------------------------------------- */
	h1{
		font-size: 5em;
		font-family: arial,helvetica;
		color: #fff;
		margin:0;
		padding:0;
	}
	.intro p{
		color: #fff;
	}

	/* Centered texts in each section
	* --------------------------------------- */
	.section{
		text-align:center;
	}



	/* Defining each section background and styles
	* --------------------------------------- */
	#section0{
		background: -webkit-gradient(linear, top left, bottom left, from(#4bbfc3), to(#7baabe));
		background: -webkit-linear-gradient(#4BBFC3, #7BAABE);
		background: linear-gradient(#4BBFC3,#7BAABE);
	}

	#section2{
		background: -webkit-gradient(linear, top left, bottom left, from(#969ac6), to(#636F8F));
		background: -webkit-linear-gradient(#969AC6, #636F8F);
		background: linear-gradient(#969AC6,#636F8F);
	}



 	/*Adding background for the slides
	* --------------------------------------- */
	#slide1{
		background: -webkit-gradient(linear, top left, bottom left, from(#7baabe), to(#969ac6));
		background: -webkit-linear-gradient(#7BAABE, #969AC6);
		background: linear-gradient(#7BAABE,#969AC6);
	}
	#slide2{
		background: -webkit-gradient(linear, top left, bottom left, from(#92a1ca), to(#76c2bd));
		background: -webkit-linear-gradient(#92a1ca, #76c2bd);
		background: linear-gradient(#92a1ca,#76c2bd);
	}


	/* Bottom menu
	* --------------------------------------- */
	#infoMenu li a {
		color: #fff;
	}
	</style>
		<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="js/jquery-pbdTabs.1.0.js"></script>
		<script type="text/javascript" src="js/micromarkdown.js"></script>
		
	    <script type="text/javascript" src="js/jquery.fullPage.js"></script>
	    <script type="text/javascript" src="js/examples.js"></script>
		
	    <script language="JavaScript" type="text/javascript">
		    initTime = (new Date()).getTime();
			
		    $(document).ready(function(){
			    
		$('#fullpage').fullpage({
				sectionsColor: ['#1bbc9b', '#4BBFC3', '#7BAABE', 'whitesmoke', '#ccddff','#00bdf4','#D0D0D0'],
				anchors: ['start-info', 'about-info', 'service-info', 'actual-info', 'contact-info', 'pbd-tabs'],
				menu: '#menu',
				scrollingSpeed: 1000,
				continuousVertical: true,
				//---------------------------------
				//verticalCentered: false,
				//to avoid problems with css3 transforms and fixed elements in Chrome, as detailed here: https://github.com/alvarotrigo/fullPage.js/issues/208
				//css3:false
			});

		        $(".tabs").pbdTabs({
				    orientation : 'h', //v,vertical,h,horizontal | renders the tab buttons above or beside the tab panel
		            orientation_btn : '#orientation_btn', // the button which can be used to toggle tab buttons orientation
		            event : 'click', // click,hover | 
		            show_tab_index : 1, // determine which tab panel to show and tab button to highlight on page load
		            height : 240, //
		            //effect : 'fadein', // not available,
		            //effect_time : 3000, // not available,
	            });
				/*input = document.getElementById('defaultmd').innerHTML.split('<!--')[1].split('-->')[0];
      document.getElementById('about_tmp').value = input;
		        $("#defaultmd").load("README.md");
				
       */$('#about').html(micromarkdown.parse($('#about_tmp').html()));
	            var note = $('#note');
	            var saveTimer,
	            	lineHeight = parseInt(note.css('line-height')),
	            	minHeight = parseInt(note.css('min-height')),
	            	lastHeight = minHeight,
	            	newHeight = 0,
	            	newLines = 0;
		
	            var countLinesRegex = new RegExp('\n','g');
	            // The input event is triggered on key press-es,
	            // cut/paste and even on undo/redo.
				note.load("note.txt");
	            note.on('input',function(e){
		            // Clearing the timeout prevents
		            // saving on every key press
		            clearTimeout(saveTimer);
	            	saveTimer = setTimeout(ajaxSaveNote, 2000);
		
	            	// Count the number of new lines
	            	newLines = note.val().match(countLinesRegex);
		
	            	if(!newLines){
	            		newLines = [];
	            	}
		
	             	// Increase the height of the note (if needed)
	            	newHeight = Math.max((newLines.length + 1)*lineHeight, minHeight);
		
	             	// This will increase/decrease the height only once per change
	             	if(newHeight != lastHeight){
	            		note.height(newHeight);
	             		lastHeight = newHeight;
	            	}
	            }).trigger('input'); // This line will resize the note on page load
	
	            function ajaxSaveNote(){
	            	// Trigger an AJAX POST request to save the note
	            	//$.post('index.php', { 'note' : note.val() });
	            	$.post('note.php', { 'note' : note.val() });
	            }
			//}
	        });
			
			function pageloadingtime(){
		        postTime = (new Date()).getTime();
				seconds = (postTime-initTime)/1000;
				document.getElementById("loadTime").innerHTML = "<strong><font color=\"#FF00CC\">(Your Page took " + seconds + " second(s) to load.)</font></strong>";
			}
			
		    window.onload = pageloadingtime;
		</script>
    </head>
    <body>
	
<select id="demosMenu">
  <option selected>Choose Demo</option>
  <option id="backgrounds">Background images</option>
  <option id="backgroundVideo">Background video</option>
  <option id="gradientBackgrounds">Gradient backgrounds</option>
  <option id="backgroundsFixed">Fixed fullscreen backgrounds</option>
  <option id="looping">Looping</option>
  <option id="noAnchor">No anchor links</option>
  <option id="scrollingSpeed">Scrolling speed</option>
  <option id="easing">Easing</option>
  <option id="callbacks">Callbacks</option>
  <option id="css3">CSS3</option>
  <option id="continuous">Continuous scrolling</option>
  <option id="normalScroll">Normal scrolling</option>
  <option id="scrollBar">Scroll bar enabled</option>
  <option id="scrolling">Scroll inside sections and slides</option>
  <option id="navigationV">Vertical navigation dots</option>
  <option id="navigationH">Horizontal navigation dots</option>
  <option id="fixedHeaders">Fixed headers</option>
  <option id="apple">Apple iPhone demo (animations)</option>
  <option id="oneSection">One single section</option>
  <option id="responsiveHeight">Responsive Height</option>
  <option id="responsiveWidth">Responsive Width</option>
  <option id="methods">Methods</option>
</select>

<ul id="menu">
	<li data-menuanchor="start-info"><a href="#start-info">First slide</a></li>
	<li data-menuanchor="about-info"><a href="#about-info">Second slide</a></li>
	<li data-menuanchor="service-info"><a href="#service-info">Third slide</a></li>
	<li data-menuanchor="actual-info"><a href="#actual-info">Fourth slide</a></li>
	<li data-menuanchor="contact-info"><a href="#contact-info">Fifth slide</a></li>
	<li data-menuanchor="pbd-tabs"><a href="#pbd-tabs">Sixth slide</a></li>
</ul>


<div id="fullpage">
	<div class="section " id="section0">
		<h1>fullPage.js</h1>
		 <input type="hidden" name="id" value="1" />
		<p>Create Beautiful Fullscreen Scrolling Websites</p>
		<img src="imgs/fullPage.png" alt="fullPage" />
	</div>
	<div class="section active" id="section1">
		<div class="slide">
			<div class="intro">
				<h1>Create Sliders</h1>
				<p>Not only vertical scrolling but also horizontal scrolling. With fullPage.js you will be able to add horizontal sliders in the most simple way ever.</p>
				<img src="imgs/slider.png" alt="slider" />
			</div>

		</div>
		<div class="slide">
			<div class="intro">
				<img src="imgs/1.png" alt="simple" />
				<h1>Simple</h1>
				<p>Easy to use. Configurable and customizable.</p>
			</div>
		</div>
		<div class="slide">
			<div class="intro">
				<img src="imgs/2.png" alt="Cool" />
				<h1>Cool</h1>
				<p>It just looks cool. Impress everybody with a simple and modern web design!</p>
			</div>
		</div>
		<div class="slide">
			<div class="intro">
				<img src="imgs/3.png" alt="Compatible" />
				<h1>Compatible</h1>
				<p>Working in modern and old browsers too! IE 8 users don't have the fault of using that horrible browser! Lets give them a chance to see your site in a proper way!</p>
			</div>
		</div>
	</div>
	<div class="section" id="section2">
		<div class="intro">
			<h1>Example</h1>
			<p>HTML markup example to define 4 sections.</p>
			<img src="imgs/example2.png" alt="example" />
		</div>
	</div>
	<div class="section" id="section3">
		<div class="intro">
			<h1>Working On Tablets</h1>
			<p>
				Designed to fit to different screen sizes as well as tablet and mobile devices.
				<br /><br /><br /><br /><br /><br />
			</p>
		</div>
		<img src="imgs/tablets.png" alt="tablets" />
	</div>
	<div class="section" id="section4">
	    <div class="intro">
		<div class="slide" id="slide1"><h1>Soft graduated colors</h1></div>
	    <div class="slide" id="slide2"><h1>Even for each slide if you want</h1></div>
		<div class="slide "><h1>Simple Demo</h1></div>
	    <div class="slide active"><h1>Only text</h1></div>
	    <div class="slide"><h1>And text</h1></div>
	    <div class="slide"><h1>And more text</h1></div>
		</div>
	</div>
	<div class="section" id="section5">
	    <div id="container">
            <h1>PBD jQuery Tabs<div class="icon-wrapper right-1"><a class="ajax-btn" href="./"><div class="ui-icon ui-icon-home"></div></a></div><div class="clear"></div></h1>
            <div id="body">
			<div class="widget">
				    <div class="widget-title">Tabs<div class="icon-wrapper right-1"><a id="orientation_btn" href="#tabs" title="Toggle tab orientation"><div class="ui-icon ui-icon-arrowthick-2-n-s"></div></a></div><div class="clear"></div></div>
				    <div class="spacer"></div>
					<div class="tabs horizontal">
			            <ul class="tabs-nav"> 
							<li class="tab-icon"><a href="#column-1"><div class="ui-icon ui-icon-info"></div></a></li>
							<li class="tab-icon"><a href="#column-2"><div class="ui-icon ui-icon-note"></div></a></li>
							<li class="tab-icon"><a href="#column-3"><div class="ui-icon ui-icon-cog"></div></a></li>
							<li class="tab-icon"><a href="#column-4"><div class="ui-icon ui-icon-link"></div></a></li>
							<li class="tab-icon"><a href="#column-5"><div class="ui-icon ui-icon-edit"></div></a></li>
							<li class="tab-icon"><a href="README.txt" data-target-id="#column-10" title="this link loads the url set in the href attribute"><div class="ui-icon ui-icon-globe"></div></a></li>
							<li class="tab-icon"><a href="http://localhost/projects/__TRASH__/index.php/welcome" data-target-id="#column-10" title="this link loads the url set in the href attribute"><div class="ui-icon ui-icon-http"></div></a></li>
                        </ul>
						
						<div id="column-1" class="tabs-panel">
						    <div class="tabs-panel-title">Info</div>
							<div id="about"></div>
							<div id="about_tmp" style="display:none;" ><?php echo $readme;?></div>
						</div>
						
						<div id="column-2" class="tabs-panel">
						    <div class="tabs-panel-title">Notes</div>
		                	<textarea id="note"><?php echo $note_content;?></textarea>
						</div>
						
						<div id="column-3" class="tabs-panel">
							<div class="tabs-panel-title">Settings</div>
							<ul class="settings">
								<li><span class="selector">orientation</span> : <span class="value">'vertical'</span>, <span class="comment">//vertical,horizontal | renders the tab buttons above or beside the tab panel</span></li>
								<li><span class="selector">orientation_btn</span> : <span class="value">'#orientation_btn'</span>, <span class="comment">// the button which can be used to toggle tab buttons orientation</span></li>
								<li><span class="selector">event</span> : <span class="value">'click'</span>, <span class="comment">// click,hover | </span></li>
								<li><span class="selector">show_tab_index</span> : <span class="value">0</span>, <span class="comment">// determine which tab panel to show and tab button to highlight on page load</span></li>
								<li><span class="selector">height</span> : <span class="value">'400px'</span>, <span class="comment">// set the height of all "tabs-panel"</span></li>
								<li></li>
							</ul>
						</div>
						
						<div id="column-4" class="tabs-panel">
							<div class="tabs-panel-title">Links</div>
							<ul>
								<li class="menu-item"><a href="http://www.facebook.com/projectblu-media">http://www.facebook.com/projectblu-media</a></li>
								<li class="menu-item"><a href="http://www.twitter.com/UKT_PBD" title="">http://www.twitter.com/UKT_PBD</a></li>
								<li class="menu-item"><a href="http://www.projectblu.com" title="">http://www.projectblu.com</a></li>
							</ul>
						</div>
						<div id="column-5" class="tabs-panel">
							<div class="tabs-panel-title">Editor</div>
							
						</div>
						<div id="column-10" class="tabs-panel">
							ajax content will be dynamically loaded here
						</div>
					</div>
					<div class="clear"></div>  
				</div>
			    <div class="clear"></div>
            </div>
		
		    <footer>
		        <p class="footer"><div id="loadTime"></div></p>
            </footer>
        </div>	
	</div>
</div>
    </body>	
</html>	