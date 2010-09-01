<?
class SasktelParser {
    
    /**
    * Grab the DOMDocument instance and query/parse 3G and non 3G files.
    * We check a cache before we actually hit Google though.
    *
    * @param Object $dom The DOMDocument instance passed from SasktelFetcher
    */
    public function __construct($dom){
        
        $this->dom = $dom;
        $this->table = $this->dom->getElementsByTagName('table');
        
        self::queryAndParse3G();
        self::queryAndParseNon3G();
        
    }
    
    /**
    * If our cache is expired, or the file doesn't exist, hit Google and get some lat/lng info.
    * After we're done we store the info and cache it.
    */
    public function queryAndParse3G(){
        
        if(SasktelCache::cacheExpired(SasktelCache::JS_CURRENTLY_3G)){ 
        
            $city_data = array();
            
            $current_3g = $this->table->item(0);
            $current_3g_cities = $current_3g->getElementsByTagName('td');
            
            foreach($current_3g_cities as $city){
                $city = $city->nodeValue;
                $city_data[] = self::_createCityDataArray($city);
            }
            
            SasktelCache::cacheJSONCityData(SasktelCache::JS_CURRENTLY_3G, json_encode($city_data), 'currently_3g');
               
        }
        
    }
    
    /**
    * If our cache is expired, or the file doesn't exist, hit Google and get some lat/lng info.
    * After we're done we store the info and cache it.
    */
    public function queryAndParseNon3G(){
        
        if(SasktelCache::cacheExpired(SasktelCache::JS_NON_3G)){            
            
            $city_data = array();
            
            for($i = 1, $len = $this->table->length; $i <= $len; $i++){
                
                $tr = $this->table->item($i);
                if(!$tr){
                    continue;
                }
                
                $tr = $tr->getElementsByTagName('tr');
                
                foreach($tr as $row){
                    
                    $td = $row->getElementsByTagName('td');
                    $city = $td->item(0)->nodeValue;
                    $date = $td->item(1)->nodeValue;
                    
                    $city_data[] = self::_createCityDataArray($city, $date);
                    
                }
                
            }
            
            SasktelCache::cacheJSONCityData(SasktelCache::JS_NON_3G, json_encode($city_data), 'non_3g');
            
        }
        
    }
    
    /**
    * Since Sasktel doesn't have a consistent format for dates, we try and remedy that.
    * 
    * @param String $date The date format we're passing in
    */
    public static function normalizeDate($date){
    
        $new_date = str_replace(array(' -', '- '), '-', $date);
        
        if(stristr($new_date, 'Q')){
        
            $date_parts = explode(' ', $new_date);
            $quarter = trim($date_parts[0]);
            $year = trim($date_parts[1]);
            $new_date = str_replace(',,', ',', $quarter . ',' . $year);
            
        }
        else if(stristr($new_date, ',')){
        
            $date_parts = explode(',', $new_date);
            $month = trim($date_parts[0]);
            $year = trim($date_parts[1]);
            $new_date = $month . ',' . $year;
            
        }
        
        return str_replace(',', ', ', $new_date);
        
    }
    
    /**
    * When we hit Google with these city names (given by Sasktel), Google has NO IDEA wtf some of these cities/towns
    * are. We can strip a few things off in the city name to help Google find the location
    *
    * @param String $city The city name
    */
    public static function normalizeCity($city){
        
        // Some quick hard-coded fixes to help Google make proper queries
        $c = str_replace(array(
            ' - Points North', // 'Collins Bay - Points North
            ' K2 Mine', // Esterhazy K2 Mine
            ' (permanent site)', // Craven, (permanent site)
            ' (Piapot Hwg #1)', // Sidewood (Piapot Hwg #1)
            ' Rptr.', // Wynyard Rptr.
        ), '', $city);
        
        $c = str_replace('White Cap IR', 'Whitecap');
        
        return $c;
        
    }
    
    /**
    * Create and return an array of all the data we need to store in 
    * the cached js file 
    *
    * @param String $city The city name to make the google query with
    * @param String $date The date (optional: if it's a non 3g location)
    */
    protected static function _createCityDataArray($city, $date = ''){
        
        $city = self::normalizeCity($city);
        
        if($date){
            $date = self::normalizeDate($date);
        }
        
        $data = SasktelFetcher::queryGoogle($city);
        $coords = $data['content']['Placemark'][0]['Point']['coordinates'];
        
        $lat = $coords[1];
        $lon = $coords[0];
        
        $ret = array(
            'city' => $city,
            'lat' => $lat,
            'lon' => $lon
        );
        
        if($date){
            $ret['date'] = $date;
        }
        
        return $ret;
        
    }
    
}