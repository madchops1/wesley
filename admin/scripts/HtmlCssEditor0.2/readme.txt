Html Css Editor
---------------

Author
------
The author of this project is Uzi Landsmann, uzi@<removeme>landsmanns.com


Description
-----------
HtmlCssEditor is a CSS editor that allows integration with a web-based HTML 
editor through a javascript API. HTML editors can use the CSS editor by placing 
it beside the main editor or opening it for use as needed. The CSS editor is 
composed of a tabbed panel that is devided into major style groups (borders, 
background etc.) and shows groups of comboboxes that allows users to choose 
(rather then write) attributes for their CSS elements. A panel inside the editor 
also shows how the element currently looks like, using the given attributes. The 
set of attributes can later be retrieved for use by the HTML editor using the 
API, which also allows clearing all attributes as well as adding a listener 
to attribute changes in the CSS editor. Techniques used in the project are XML, 
HTML and javascript.

The CSS editor was created thanks to the CSS knowledge found in W3 Schools. You
can find all about css in http://www.w3schools.com/css/default.asp.


License
-------
The CSS editor is released under the GNU GENERAL PUBLIC LICENSE. The GPL details
can be found under the attached gpl.txt document. 


Usage
-----
The editor allows integration through aan easy to use API. To integrate with the
CSS editor, follow the following steps:


1. In your HTML editor, prepare a placeholder for the CSS Editor, and give it
a unique id.

e. g.: 
<td id="css" class="editor">Loading the css editor...</td>


2. Include the editor's main javascript file by including the following row in
your editors HTML header:

<script type="text/javascript" src="csseditor/javascript/css.js"></script>


3. Create a link to the CSS editor stylesheet in your editors HTML header:

<link rel="stylesheet" href="csseditor/style/css.css" type="text/css" />

Edit this style sheet to alter the CSS editor's looks.


4. If you want the CSS editor to be visible from the beginning, add a call to the insertEditor() function in your <body> tag, e.g.:  
  
<body onLoad="insertEditor(css)">

Otherwise, you can also call this function whenever you want to show the CSS editor. Remember to include the unique id of the CSS editor's placeholder as argument when calling the insertEditor() function. 


The CSS Editor Project
----------------------
The CSS Editor Project is hosted by SourceForge. You can find it in http://sourceforge.net/projects/htmlcsseditor/

Good luck - 

Uzi Landsmann
