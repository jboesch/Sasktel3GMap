<?
require('includes/SasktelCache.php');
require('includes/SasktelFetcher.php');
require('includes/SasktelParser.php');

$fetcher = new SasktelFetcher();
$parser = new SasktelParser($fetcher->getDOMInstance());
?>
<html>
<head>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript">google.load('jquery','1.4.2');</script>
    <script type="text/javascript" src="http://maps.google.com?file=api&amp;v=2.x&amp;key=<?= SasktelFetcher::$google_key; ?>"></script>
    <script type="text/javascript" src="js/currently_3g.js"></script>
    <script type="text/javascript" src="js/non_3g.js"></script>
</head>
<body>
        
    <div id="map_canvas" style="width:600px;height:800px;"></div>
    <script type="text/javascript" src="js/SasktelMap.js"></script>
    <script type="text/javascript">
        $(function(){
            SasktelMap.init();
        });
    </script>
</body>
</html>