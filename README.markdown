Custom HelpSpot Widget
======================

PLEASE NOTE:
============
This version is longer supported. [Please go here to get the latest version](https://github.com/plasticbrain/HelpSpot-Custom-Widget-1.0 "The latest version").

Description
-----------
If you've ever used [HelpSpot](http://www.helpspot.com "HelpSpot") then, like me, 
you were probably a bit disappointed by the lack of customization that their 
"widgets" offer. This plugin basically clones the default HelpSpot widget, but 
it gives you much more flexibility, and the ability to customize just about 
every aspect.

Current Version
---------------
This is the first version. At this point, I would definitely consider it to be in beta...

Requirements
------------
* Obviously, you need an account with [HelpSpot](http://www.helpspot.com "HelpSpot")
* The plugin itself requires jQuery to work

Installation & Basic Usage
--------------------------
    <!-- include our stylesheet -->
    <link href="widget.css" rel="stylesheet" type="text/css" media="screen" />

    <!-- include jQuery -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    
    <!-- include widget.js -->
    <script type="text/javascript" src="widget.js"></script>
    
    <!--setup and initialize the widget plugin -->
    <script type="text/javascript">
    $(function(){
		
        // you could probably bind this to just about any element...
        $('html').HelpSpotWidget({
				
            // the base url to where HelpSpot is installed (***include trailing slash***)
            host: 'http://support.yourdomain.com/',
				
            // the base url to where the widget plugin is located (***include trailing slash***)
            // only needs to be set if the widget files are outside of the current directory
            base: ''
        });
    });	
    </script>

Documentation
-------------
You can find the plugin documentation in the docs/ directory. You can also find the documentation, as well as a live sample, at **[http://labs.mikeeverhart.net/helpspot](http://labs.mikeeverhart.net/helpspot "Documentation and a Live Sample")**

Plugin Author
--------------
This plugin was created by Mike Everhart. You can find me around the web at:

* My Personal Site: [MikeEverhart.net](http://www.mikeeverhart.net "My personal site")
* My Side Project: [plasticbrain.net](http://www.plasticbrain.net "My part time project")
* My Social Life: [Facebook](https://www.facebook.com/plasticbrain "Friend me on Facebook!")

Reporting Bugs
--------------
So you found a bug? Hey, nobody's perfect, right?

Please use [GitHub's Issue Tracker](https://github.com/plasticbrain/HelpSpot-Custom-Widget/issues/new "Submit a Bug") to submit a bug report.

Suggestions & Improvements
--------------------------
Got an idea to make this plugin even better? Well, lucky for you, you have two choices!

* Email your suggestions to **[feedback@plasticbrain.net](mailto:feedback@plasticbrain.net "Submit Feedback")**
* Or, you can even fork this plugin and create your own version!

Future Plans
------------
The main downside to the plugin in its current state is that it loads the forms within an iFrame. In the next version, I'd like to get rid of the iFrame and replace it with a lightbox.