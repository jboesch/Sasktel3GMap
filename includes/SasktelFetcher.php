<?
class SasktelFetcher {
    
    const GMAPS_CITY_DATA_URL = 'http://maps.google.com/maps/geo?output=json&oe=utf-8&q=%s,sk&key=%s';
    public static $google_key = 'ABQIAAAAUoCdQkHgn6AdH-3uaJuSjBT2yXp_ZAY8_ufC3CFXhHIE1NvwkxSSESTGGhHqU9prLtAH-ZijfvJD_w';
    
    public function __construct($file = 'includes/static_data.php'){
        
        $this->dom = new DOMDocument();
        @$this->dom->loadHTMLFile($file);
        $this->dom->preserveWhiteSpace = false;
        
    }
    
    public function getDOMInstance(){
        
        return $this->dom;
        
    }
    
    public static function getGoogleCityDataUrl($city){
        
        return sprintf(self::GMAPS_CITY_DATA_URL, urlencode($city), self::$google_key);
        
    }
    
    public static function queryGoogle($city){
        
        $url = self::getGoogleCityDataUrl($city);
        
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
        );
    
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);
    
        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;
        $header['content'] = json_decode($content, true);
        
        return $header;
        
    }
    
}