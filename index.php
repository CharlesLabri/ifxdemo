<?php
		require("inc/config.php");
		require(ROOT_PATH . "inc/scribeQueries.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="/css/stylesheet.css" rel="stylesheet" type="text/css">
    </head>
    <body>
		<br>
        <h1>iFixit Demo {iFixit API to Local MySQL Storage}</h1>
		<div id="instructions">
			<h2>Instructions for Use</h3>
				<p> The purpose of this application is to query the <a href="https://www.ifixit.com/api/2.0/doc/Wikis">iFixit API</a> for content in their wiki and manipulate it locally with <a href="https://github.com/guardian/scribe">Scribe</a> and <a href="http://www.mysql.com/">MySQL</a>. <br>
					<ul>
					<li>Using the address bar, enter a namespace and title (e.g. http://ifxdemo.charleslabri.com/namespace/title) to load the data from the iFixit API.</li>
					<li>{Get a full listing of the available namespaces and titles at the <a href="https://www.ifixit.com/api/2.0/doc/Wikis">iFixit API</a>}</li>
					<li>The 'Save Displayed Content' button will save the currently loaded Namespace, Title, and HTML output {of scribe} to the database.</li>
					<li>The 'Load Fresh Content' button will query the iFixit API directly without looking at the local MySQL database.</li>
					<li>If local content is found with a matching Namespace and Title, it will be loaded instead of querying the iFixit API.</li>
					<li>Get started with an example at <a href="http://ifxdemo.charleslabri.com/CATEGORY/mac">this</a> link.</li>
					</ul>
		</div>
		<h3>Loaded Content:</h3>
        <div class="toolbar">
            <button id="load">Load Fresh Content</button>
			<button id="save">Save Displayed Content</button>
        </div>
        <div class="scribe" contenteditable="true"></div>
        <h3>Transmogrified data:</h3>
        <textarea class="scribe-html" rows="15" readonly></textarea>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="/inc/jquery.notifyBar.js"></script>
        <script src="/bower_components/requirejs/require.js" data-main="/scribeScript"></script>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
    </body>
</html>
<?php die; ?>
