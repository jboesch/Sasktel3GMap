<?
class SasktelCache {
    
    const CACHE_TIME = 86400; // (60 * 60 * 24);
    const JS_NON_3G = 'js/non_3g.js';
    const JS_CURRENTLY_3G = 'js/currently_3g.js'; 
    
    public static function cacheExpired($cachefile){
        
        // Serve from the cache if it is younger than $cachetime
        if(file_exists($cachefile) && time() - self::CACHE_TIME < filemtime($cachefile)){
            return false;
        }
        
        return true;
        
    }
    
    public static function cacheJSONCityData($cachefile, $data, $prepended_var = ''){
        
        // Cache the output to a file
        $fp = fopen($cachefile, 'w');
        
        if($prepended_var){
            $data = 'var ' . $prepended_var . ' = ' . $data;
        }
        
        fwrite($fp, $data . ';');
        fclose($fp);

        
    }
    
}