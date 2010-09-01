var SasktelMap = (function(){
    
    // The map id of the gmap
    var _map_id = "map_canvas";
    // The icon for current 3g icons
    var _CURRENTLY_3G_ICON = 'green-dot.png'
    // Icons for non 3g icons
    var _NON_3G_ICON_GROUPS = {
        'Sept': 'yellow-dot.png',
        'Oct': 'orange-dot.png',
        'Nov': 'red-dot.png'
    };
    
    // Anything with a Q gets a black dot
    var _Q_ICON = 'black-dot.png'
    
    return {
        
        map: {},
        
        /**
        * Set things up and initialize the google map
        */
        init: function(){
            
            this.gMap_init();
            
        },
        
        /**
        * Draw the map then draw some markers of current 3g networks
        */
        gMap_init: function(){
            
            var self = this;
            
            self.map = new GMap2(document.getElementById(_map_id));
            self.map.setCenter(new GLatLng(50.4500, -104.6100), 6);
            
            self.gMap_drawCurrent3G();
            
        },
        
        /**
        * Draw the current 3g markers
        */
        gMap_drawCurrent3G: function(){
            
            var self = this;
            
            self.gMap_drawMarkers(currently_3g, _CURRENTLY_3G_ICON);
            
        },
        
        /**
        * Draw the any markers
        *
        * @param Object data The data for each set of non_3g/current_3g locations
        * @param String icon The icon for the markers
        */
        gMap_drawMarkers: function(data, icon){
            
            var self = this;
            
            var icon_img = new GIcon(G_DEFAULT_ICON);
            icon_img.iconSize = new GSize(32, 32);
            icon_img.image = "images/" + icon;
            
            var marker_options = { icon: icon_img };
            
            for(var i = 0, len = data.length; i < len; i++){
                var point = new GLatLng(data[i].lat, data[i].lon);
                self.map.addOverlay(new GMarker(point, marker_options));
            }
            
        }
        
    };
    
})();