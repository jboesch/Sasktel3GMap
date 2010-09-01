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
    <script type="text/javascript">
        
        function initializeMap() {
		    
            var map = new GMap2(document.getElementById("map_canvas"));
            map.setCenter(new GLatLng(50.4500, -104.6100), 6);
            
            for(var i = 0; i < non_3g.length; i++){
                var point = new GLatLng(non_3g[i].lat, non_3g[i].lon);
                map.addOverlay(new GMarker(point));
            }
            
        }
        
        $(function(){
            initializeMap();
        });
        
    </script>
</head>
<body>
        
    <div id="map_canvas" style="width:600px;height:800px;"></div>
    
</body>
</html>