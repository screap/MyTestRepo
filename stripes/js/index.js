function showHide(id) {
	var display = document.getElementById(id).style.display;
	if (display == 'none') { document.getElementById(id).style.display='block';
	} else { document.getElementById(id).style.display='none'; }
}

<!-- Piwik -->
var pkBaseURL = (("https:" == document.location.protocol) ? "https://piwik.screap.de/" : "http://piwik.screap.de/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
<!-- End Piwik Tag -->