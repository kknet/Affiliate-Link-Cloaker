
$iplist = array(file_get_contents('https://github.com/besoeasy/Affiliate-Link-Cloaker/raw/master/core/ip.txt'));
$bot_list = array(file_get_contents('https://github.com/besoeasy/Affiliate-Link-Cloaker/raw/master/core/bot.txt'));
$host_list = array(file_get_contents('https://github.com/besoeasy/Affiliate-Link-Cloaker/raw/master/core/host.txt'));


$date     = date('n/j/Y h:i A');
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

$ua       = getenv('HTTP_USER_AGENT');
$host     = gethostbyaddr($ip);
$blocked  = false;
$user     = true;
$files    = array("log_visitors.txt", "log_mobile.txt", "log_bots.txt");

$key = array_rand($user_links);
$user_link = $user_links[$key];

$key = array_rand($block_links);
$block_link = $block_links[$key];

$get = '[]';
$post = '[]';
if(!empty($_GET))
{
    $get = json_encode($_GET);
}
if(!empty($_POST))
{
    $post = json_encode($_POST);
}


if($js_log)
{
    exit;
}


foreach($iplist as $value)
{
    if($helper->ip_range($ip, $value))
    {
        $user    = false;
        $blocked = true;
        break;
    }
}

foreach($bot_list as $bot)
{
    if(preg_match("#" . $bot ."#is", getenv("HTTP_USER_AGENT")))
    {
        $user    = false;
        $blocked = true;
        break;
    }
}

foreach($host_list as $checkhost)
{
    if($helper->handle_host($checkhost))
    {
        $blocked = true;
        $user = false;
        break;
    }
}



if(!array_key_exists('HTTP_REFERER', $_SERVER))
{
    $referrer = '(empty)';
}
elseif($referrer_redirect == true)
{
    $referrer = getenv('HTTP_REFERER');

    foreach($referrer_list as $key=>$referrer)
    {
        if(is_numeric($key))
        {
            if(preg_match("#" . $referrer ."#is", $_SERVER['HTTP_REFERER']))
            {
                $blocked = true;
                break;
            }
        }
        elseif(is_string($key))
        {
            if(preg_match("#" . $key ."#is", $_SERVER['HTTP_REFERER']))
            {
                $user_links = explode("\n", $referrer[0]);
                break;
            }
        }
    }
}

if($helper->is_tor())
{
    if($deny_tor)
    {
        $blocked = true;
    }

    $tor_log = "True";
}
else
{
    $tor_log = "False";
}

if(is_string($helper->is_proxy()))
{
    if($deny_proxy)
    {
        $blocked = true;
    }

    $tor_log = "Real IP: ".$helper->is_proxy()." | ".$tor_log;
}

if($enable_mobile == true)
{
    $mobile = new Mobile($mobile_links);

    if($mobile->is_mobile == true)
    {
        $iplog = 'mobile_ips';
        $user_links = array($mobile->mobile_url);

        $user = false;
        $file = "log_mobile.txt";
    }
}

if($user == true && $mobile->is_mobile == false)
{
    $iplog = 'user_ips';
    $file = "log_visitors.txt";
}


elseif($user == false && $blocked == true)
{
    $iplog = 'blocked_ips';
    $file = "log_bots.txt";
}

if(is_array($_GET) && array_key_exists("tzo", $_GET))
{
    $ajax = false;
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $ajax = true;
    }

    if(!$ajax)
    {
        if(file_exists('.301hits'))
        {
            $number = file_get_contents('.301hits');
        }
        else
        {
            $number = 0;
        }
        
        $fh = fopen('.301hits', "w+");
        fwrite($fh, $number+1);
        fclose($fh);
    }
}


$log = "{$date} | UA: {$ua} | IP: {$ip}{N}"
      ."HOST: {$host} | REFERRER: {$referrer} | TOR: {$tor_log} | GET DATA: {$get} | POST DATA: {$post}{N}{N}";
$log = str_replace("{N}", "\n", $log);

$helper->log_uniques($iplog);
if($enable_logs == true && $javascript_data == false)
{
    $fh = fopen($file, 'a+');
    fwrite($fh, $log);
    fclose($fh);
}


if($blocked == true)
{

    if($fetch_page == true)
    {
        $fetch = new Fetch_site(null, implode("\n", $block_links));
        $fetch->html = $helper->user_info($javascript_data, $fetch->html);
        echo $fetch->html;
    }
    else
    {
        $key = array_rand($block_links);
        $block_link = $block_links[$key];

        $fh = fopen($file, 'a+');
        fwrite($fh, $log);
        fclose($fh);

        header('Status: 301 Moved Permanently', true, 301);
        header('Location: '. $block_link);
    }
}
else
{
    if($fetch_page == true)
    {
        $fetch = new Fetch_site(null, implode("\n", $user_links));
        $fetch->html = $helper->user_info($javascript_data, $fetch->html);
        echo $fetch->html;
    }
    else
    {
        $key = array_rand($user_links);
        $user_link = $user_links[$key];

        $fh = fopen($file, 'a+');
        fwrite($fh, $log);
        fclose($fh);

        header('Status: 301 Moved Permanently', true, 301);
        header('Location: '. $user_link);
    }
}

class Fetch_site
{
    public $url;

    public $html;

    public function __construct($sites=null, $link_list=null)
    {
        $this->is_curl_installed();

        if($link_list !== null)
        {
            $this->choose_site($link_list);
        }
        
        $this->load_site($this->url);
    }

    public function choose_site($file=null)
    {
        $file       = explode("\n", $file);
        $key        = rand(0, (count($file)-1));
        $this->url  = trim($file[$key]);
    }

    public function load_site($url)
    {


        $useragent = array('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36','Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.246',
);
        $rand = array_rand($useragent);

        $ch      = curl_init();
        $options = array(
            CURLOPT_URL            => $url,
            CURLOPT_HEADER         => 0,
            CURLOPT_USERAGENT      => $useragent[$rand],
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_ENCODING       => "",
            CURLOPT_AUTOREFERER    => 1,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_MAXREDIRS      => 5,
            
        );
        curl_setopt_array($ch,$options);

        $result = curl_exec($ch);

        if(!$result)
        {
            return curl_error($ch);
        }

        curl_close($ch);
        $result = preg_replace("/<head>/i", "<head><base href='$url' />", $result, 1);
        $this->html = $result;
    }

    public function is_curl_installed()
    {
        if(!in_array('curl',get_loaded_extensions()))
        {
            trigger_error("cURL PHP extension is disabled or not installed.",E_USER_ERROR);
        }
    }
}

class Helper
{
    public function tally_logs($logs)
    {
        if(!is_array($logs))
        {
            return 'Error';
        }

        $log = '';

        foreach($logs as $file)
        {
            if(!file_exists($file))
            {
                continue;
            }
            switch ($file) {
                case 'user_ips':
                    $section = 'Visitors:'."\n";
                break;

                case 'blocked_ips':
                    $section = 'Bots and Blocked IPs/Hosts:'."\n";
                break;

                case 'mobile_ips':
                    $section = 'Mobile Visitors:'."\n";
                break;
            }

            $ips = file_get_contents($file);
            $ips = explode("\n", $ips);
            $total = count($ips);
            foreach($ips as $key=>$ip)
            {
                if(strlen($ip) > 0)
                {
                    $ips[$key] = trim($ip);
                }
                else
                {
                    unset($ips[$key]);
                }
            }
            $ips = array_unique($ips);
            $ips = count($ips);

            $log .= $section;
            $log .= "Total hits: ".$total."\n";
            $log .= "Unique hits: ".$ips."\n";
            $log .= "----------------------------\n\n";
        }

        return $log;
    }

    public function log_uniques($file)
    {
        $cur/*rent IP*/ = getenv('REMOTE_ADDR');
        $unique = true;
        if(file_exists($file))
        {
            $ips = explode("\n", file_get_contents($file));
            if(in_array($cur, $ips))
            {
                $unique = false;
            }
        }

        $fh = fopen($file, "a+");
        fwrite($fh, $cur."\n");
        fclose($fh);
    }

    public function post_user_info()
    {
        if(!isset($_POST['log']))
        {
            return false;
        }

        $id = $_POST['id'];
        $log = $_POST['log'];

        if($id == '1')
        {
            $id = 'log_visitors.txt';
        }
        elseif($id == '2')
        {
            $id = 'log_mobile.txt';
        }
        elseif($id == '3')
        {
            $id = 'log_bots.txt';
        }

        $log = str_replace("{N}", "\n", $log);

        $fh = fopen($id, 'a+');
        fwrite($fh, $log."\n\n");
        fclose($fh);

        return true;
    }

    public function user_info($enabled, $html)
    {
        if(!$enabled)
        {
            return $html;
        }

        global $log;
        global $file;

        if($file == 'log_visitors.txt')
        {
            $file = '1';
        }
        elseif($file == 'log_mobile.txt')
        {
            $file = '2';
        }
        elseif($file == 'log_bots.txt')
        {
            $file = '3';
        }

        $php_log = str_replace("\n", "", $log);
        $php_log = str_replace("'", "\'", $php_log);
        $php_self = 'http://'.getenv('HTTP_HOST').$_SERVER['PHP_SELF'];

        $js = "
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></script>
<script>
    var log_text = '';
    var id = ".$file.";
    var json = [];
    var screen_resolution = screen.width+'x'+screen.height;
    var plugins = window.navigator.plugins;
    var platform = window.navigator.platform;
    var php_data = '".$php_log."';

    plugin_log = '';
    l = plugins.length;
    for(i in plugins) {
        plugin = plugins[i].name;
        if(plugin !== null && plugin !== 'item' && plugin !== 'namedItem' && plugin !== 'refresh' && plugin !== undefined) {
            if(i < l && i > 0) {
                plugin_log += ', '; //only add the comma if it isn't the last item in the loop
            }
            plugin_log += plugin
        }
    }

    log_text += php_data
             +  'Client-side data:'
             +  ' OS: '+platform
             +  ' | Screen Resolution: '+screen_resolution
             +  ' | Installed Plugins: '+plugin_log;

    var str_data = log_text;
    jQuery.ajax('".$php_self."', {
        data:{
            'log': str_data,
            'id': id
        },
        type:'POST',
    });

</script>
";
        $html = str_replace('</body>', $js.'</body>', $html);

        return $html;
    }

    public function is_proxy()
    {
        foreach(array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 
                      'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 
                      'REMOTE_ADDR', 'HTTP_X_REMOTECLIENT_IP') as $key)
        {
            if(in_array($key, $_SERVER))
            {
                return $_SERVER[$key];
            }
        }

        return false;
    }

    public function is_tor() 
    {
        $reverse_client_ip = implode('.', array_reverse(explode('.', $_SERVER['REMOTE_ADDR'])));
        $reverse_server_ip = implode('.', array_reverse(explode('.', $_SERVER['SERVER_ADDR'])));

        $hostname = $reverse_client_ip . "." . $_SERVER['SERVER_PORT'] . "." . $reverse_server_ip . ".ip-port.exitlist.torproject.org";

        return gethostbyname($hostname) == "127.0.0.2";
    }

    private function _reverse_ip_octets($inputip)
    {
        $ipoc = explode(".",$inputip);
        return $ipoc[3].".".$ipoc[2].".".$ipoc[1].".".$ipoc[0];
    }

    public function random_date()
    {
        $low = '732045693';
        $high = time();

        $random = rand($low, $high);

        return date("D, d M Y H:i:s",$random);
    }

    public function handle_host($check_host)
    {
        $host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        if(preg_match("#".$check_host."#is", $host))
        {
            return true;
        }
    }

    public function ip_range($ip, $range)
    {
        if(strpos($range, '-') == false && strpos($range, '/') == false)
        {
            if($ip == $range)
            {
                return true;
            }

            return false;
        }

        if(strpos($range, '/'))
        {
            list($subnet, $bits) = explode('/', $range);

            $ip      = ip2long($ip);
            $subnet  = ip2long($subnet);
            $mask    = -1 << (32 - $bits);
            $subnet &= $mask; 

            return ($ip & $mask) == $subnet;
        }
        else
        {
            $range       = explode('-',trim($range));
            $range_start = ip2long($range[0]);
            $range_end   = ip2long($range[1]);
            $ip          = ip2long($ip);

            if($ip >= $range_start && $ip <= $range_end)
            {
                return true;
            }
        }

        return false;
    }


  

    private function is_tomorrow($then, $now)
    { /* script can erase itself (and logs) from the server | currently is set to 7 days (168 hours) */
        if($now >= $then + (60 * 60 * 168))
        {
            return true;
        }
    }

   
    public function is_assoc($array)
    {
      return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
}


//minified version of the mobile detection class.
class Mobile{public $is_mobile = false; public $mobile_url = ''; public function __construct($links){$this->mobile_url=$this->get_mobile_url($links); $this->mobile_device_detect(true,true,true,true,true,true,true,$this->mobile_url);}public function mobile_device_detect($iphone=true,$ipad=true,$android=true,$opera=true,$blackberry=true,$palm=true,$windows=true,$mobileredirect=false){$mobile_browser=false;$user_agent=$_SERVER['HTTP_USER_AGENT'];$accept=$_SERVER['HTTP_ACCEPT'];switch(true){case(preg_match('/ipad/i',$user_agent));$mobile_browser=$ipad;$status='Apple iPad';if(substr($ipad,0,4)=='http'){$mobileredirect=$ipad;}break;case(preg_match('/ipod/i',$user_agent)||preg_match('/iphone/i',$user_agent));$mobile_browser=$iphone;$status='Apple';if(substr($iphone,0,4)=='http'){$mobileredirect=$iphone;}break;case(preg_match('/android/i',$user_agent));$mobile_browser=$android;$status='Android';if(substr($android,0,4)=='http'){$mobileredirect=$android;}break;case(preg_match('/opera mini/i',$user_agent));$mobile_browser=$opera;$status='Opera';if(substr($opera,0,4)=='http'){$mobileredirect=$opera;}break;case(preg_match('/blackberry/i',$user_agent));$mobile_browser=$blackberry;$status='Blackberry';if(substr($blackberry,0,4)=='http'){$mobileredirect=$blackberry;}break;case(preg_match('/(pre\/|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i',$user_agent));$mobile_browser=$palm;$status='Palm';if(substr($palm,0,4)=='http'){$mobileredirect=$palm;}break;case(preg_match('/(iris|3g_t|windows ce|opera mobi|windows ce;smartphone;|windows ce;iemobile)/i',$user_agent));$mobile_browser=$windows;$status='Windows Smartphone';if(substr($windows,0,4)=='http'){$mobileredirect=$windows;}break;case(preg_match('/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|Maemo|WindowsCE|Linux armv61|Linux arm7tdmi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i',$user_agent));$mobile_browser=true;$status='Mobile matched on piped preg_match';break;case((strpos($accept,'text/vnd.wap.wml')>0)||(strpos($accept,'application/vnd.wap.xhtml+xml')>0));$mobile_browser=true;$status='Mobile matched on content accept header';break;case(isset($_SERVER['HTTP_X_WAP_PROFILE'])||isset($_SERVER['HTTP_PROFILE']));$mobile_browser=true;$status='Mobile matched on profile headers being set';break;case(in_array(strtolower(substr($user_agent,0,4)),array('1207'=>'1207','3gso'=>'3gso','4thp'=>'4thp','501i'=>'501i','502i'=>'502i','503i'=>'503i','504i'=>'504i','505i'=>'505i','506i'=>'506i','6310'=>'6310','6590'=>'6590','770s'=>'770s','802s'=>'802s','a wa'=>'a wa','acer'=>'acer','acs-'=>'acs-','airn'=>'airn','alav'=>'alav','asus'=>'asus','attw'=>'attw','au-m'=>'au-m','aur '=>'aur ','aus '=>'aus ','abac'=>'abac','acoo'=>'acoo','aiko'=>'aiko','alco'=>'alco','alca'=>'alca','amoi'=>'amoi','anex'=>'anex','anny'=>'anny','anyw'=>'anyw','aptu'=>'aptu','arch'=>'arch','argo'=>'argo','bell'=>'bell','bird'=>'bird','bw-n'=>'bw-n','bw-u'=>'bw-u','beck'=>'beck','benq'=>'benq','bilb'=>'bilb','blac'=>'blac','c55/'=>'c55/','cdm-'=>'cdm-','chtm'=>'chtm','capi'=>'capi','cond'=>'cond','craw'=>'craw','dall'=>'dall','dbte'=>'dbte','dc-s'=>'dc-s','dica'=>'dica','ds-d'=>'ds-d','ds12'=>'ds12','dait'=>'dait','devi'=>'devi','dmob'=>'dmob','doco'=>'doco','dopo'=>'dopo','el49'=>'el49','erk0'=>'erk0','esl8'=>'esl8','ez40'=>'ez40','ez60'=>'ez60','ez70'=>'ez70','ezos'=>'ezos','ezze'=>'ezze','elai'=>'elai','emul'=>'emul','eric'=>'eric','ezwa'=>'ezwa','fake'=>'fake','fly-'=>'fly-','fly_'=>'fly_','g-mo'=>'g-mo','g1 u'=>'g1 u','g560'=>'g560','gf-5'=>'gf-5','grun'=>'grun','gene'=>'gene','go.w'=>'go.w','good'=>'good','grad'=>'grad','hcit'=>'hcit','hd-m'=>'hd-m','hd-p'=>'hd-p','hd-t'=>'hd-t','hei-'=>'hei-','hp i'=>'hp i','hpip'=>'hpip','hs-c'=>'hs-c','htc '=>'htc ','htc-'=>'htc-','htca'=>'htca','htcg'=>'htcg','htcp'=>'htcp','htcs'=>'htcs','htct'=>'htct','htc_'=>'htc_','haie'=>'haie','hita'=>'hita','huaw'=>'huaw','hutc'=>'hutc','i-20'=>'i-20','i-go'=>'i-go','i-ma'=>'i-ma','i230'=>'i230','iac'=>'iac','iac-'=>'iac-','iac/'=>'iac/','ig01'=>'ig01','im1k'=>'im1k','inno'=>'inno','iris'=>'iris','jata'=>'jata','kddi'=>'kddi','kgt'=>'kgt','kgt/'=>'kgt/','kpt '=>'kpt ','kwc-'=>'kwc-','klon'=>'klon','lexi'=>'lexi','lg g'=>'lg g','lg-a'=>'lg-a','lg-b'=>'lg-b','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-f'=>'lg-f','lg-g'=>'lg-g','lg-k'=>'lg-k','lg-l'=>'lg-l','lg-m'=>'lg-m','lg-o'=>'lg-o','lg-p'=>'lg-p','lg-s'=>'lg-s','lg-t'=>'lg-t','lg-u'=>'lg-u','lg-w'=>'lg-w','lg/k'=>'lg/k','lg/l'=>'lg/l','lg/u'=>'lg/u','lg50'=>'lg50','lg54'=>'lg54','lge-'=>'lge-','lge/'=>'lge/','lynx'=>'lynx','leno'=>'leno','m1-w'=>'m1-w','m3ga'=>'m3ga','m50/'=>'m50/','maui'=>'maui','mc01'=>'mc01','mc21'=>'mc21','mcca'=>'mcca','medi'=>'medi','meri'=>'meri','mio8'=>'mio8','mioa'=>'mioa','mo01'=>'mo01','mo02'=>'mo02','mode'=>'mode','modo'=>'modo','mot '=>'mot ','mot-'=>'mot-','mt50'=>'mt50','mtp1'=>'mtp1','mtv '=>'mtv ','mate'=>'mate','maxo'=>'maxo','merc'=>'merc','mits'=>'mits','mobi'=>'mobi','motv'=>'motv','mozz'=>'mozz','n100'=>'n100','n101'=>'n101','n102'=>'n102','n202'=>'n202','n203'=>'n203','n300'=>'n300','n302'=>'n302','n500'=>'n500','n502'=>'n502','n505'=>'n505','n700'=>'n700','n701'=>'n701','n710'=>'n710','nec-'=>'nec-','nem-'=>'nem-','newg'=>'newg','neon'=>'neon','netf'=>'netf','noki'=>'noki','nzph'=>'nzph','o2 x'=>'o2 x','o2-x'=>'o2-x','opwv'=>'opwv','owg1'=>'owg1','opti'=>'opti','oran'=>'oran','p800'=>'p800','pand'=>'pand','pg-1'=>'pg-1','pg-2'=>'pg-2','pg-3'=>'pg-3','pg-6'=>'pg-6','pg-8'=>'pg-8','pg-c'=>'pg-c','pg13'=>'pg13','phil'=>'phil','pn-2'=>'pn-2','pt-g'=>'pt-g','palm'=>'palm','pana'=>'pana','pire'=>'pire','pock'=>'pock','pose'=>'pose','psio'=>'psio','qa-a'=>'qa-a','qc-2'=>'qc-2','qc-3'=>'qc-3','qc-5'=>'qc-5','qc-7'=>'qc-7','qc07'=>'qc07','qc12'=>'qc12','qc21'=>'qc21','qc32'=>'qc32','qc60'=>'qc60','qci-'=>'qci-','qwap'=>'qwap','qtek'=>'qtek','r380'=>'r380','r600'=>'r600','raks'=>'raks','rim9'=>'rim9','rove'=>'rove','s55/'=>'s55/','sage'=>'sage','sams'=>'sams','sc01'=>'sc01','sch-'=>'sch-','scp-'=>'scp-','sdk/'=>'sdk/','se47'=>'se47','sec-'=>'sec-','sec0'=>'sec0','sec1'=>'sec1','semc'=>'semc','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','sk-0'=>'sk-0','sl45'=>'sl45','slid'=>'slid','smb3'=>'smb3','smt5'=>'smt5','sp01'=>'sp01','sph-'=>'sph-','spv '=>'spv ','spv-'=>'spv-','sy01'=>'sy01','samm'=>'samm','sany'=>'sany','sava'=>'sava','scoo'=>'scoo','send'=>'send','siem'=>'siem','smar'=>'smar','smit'=>'smit','soft'=>'soft','sony'=>'sony','t-mo'=>'t-mo','t218'=>'t218','t250'=>'t250','t600'=>'t600','t610'=>'t610','t618'=>'t618','tcl-'=>'tcl-','tdg-'=>'tdg-','telm'=>'telm','tim-'=>'tim-','ts70'=>'ts70','tsm-'=>'tsm-','tsm3'=>'tsm3','tsm5'=>'tsm5','tx-9'=>'tx-9','tagt'=>'tagt','talk'=>'talk','teli'=>'teli','topl'=>'topl','hiba'=>'hiba','up.b'=>'up.b','upg1'=>'upg1','utst'=>'utst','v400'=>'v400','v750'=>'v750','veri'=>'veri','vk-v'=>'vk-v','vk40'=>'vk40','vk50'=>'vk50','vk52'=>'vk52','vk53'=>'vk53','vm40'=>'vm40','vx98'=>'vx98','virg'=>'virg','vite'=>'vite','voda'=>'voda','vulc'=>'vulc','w3c '=>'w3c ','w3c-'=>'w3c-','wapj'=>'wapj','wapp'=>'wapp','wapu'=>'wapu','wapm'=>'wapm','wig '=>'wig ','wapi'=>'wapi','wapr'=>'wapr','wapv'=>'wapv','wapy'=>'wapy','wapa'=>'wapa','waps'=>'waps','wapt'=>'wapt','winc'=>'winc','winw'=>'winw','wonu'=>'wonu','x700'=>'x700','xda2'=>'xda2','xdag'=>'xdag','yas-'=>'yas-','your'=>'your','zte-'=>'zte-','zeto'=>'zeto','acs-'=>'acs-','alav'=>'alav','alca'=>'alca','amoi'=>'amoi','aste'=>'aste','audi'=>'audi','avan'=>'avan','benq'=>'benq','bird'=>'bird','blac'=>'blac','blaz'=>'blaz','brew'=>'brew','brvw'=>'brvw','bumb'=>'bumb','ccwa'=>'ccwa','cell'=>'cell','cldc'=>'cldc','cmd-'=>'cmd-','dang'=>'dang','doco'=>'doco','eml2'=>'eml2','eric'=>'eric','fetc'=>'fetc','hipt'=>'hipt','http'=>'http','ibro'=>'ibro','idea'=>'idea','ikom'=>'ikom','inno'=>'inno','ipaq'=>'ipaq','jbro'=>'jbro','jemu'=>'jemu','jigs'=>'jigs','kddi'=>'kddi','keji'=>'keji','kyoc'=>'kyoc','kyok'=>'kyok','leno'=>'leno','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-g'=>'lg-g','lge-'=>'lge-','libw'=>'libw','m-cr'=>'m-cr','maui'=>'maui','maxo'=>'maxo','midp'=>'midp','mits'=>'mits','mmef'=>'mmef','mobi'=>'mobi','mot-'=>'mot-','moto'=>'moto','mwbp'=>'mwbp','mywa'=>'mywa','nec-'=>'nec-','newt'=>'newt','nok6'=>'nok6','noki'=>'noki','o2im'=>'o2im','opwv'=>'opwv','palm'=>'palm','pana'=>'pana','pant'=>'pant','pdxg'=>'pdxg','phil'=>'phil','play'=>'play','pluc'=>'pluc','port'=>'port','prox'=>'prox','qtek'=>'qtek','qwap'=>'qwap','rozo'=>'rozo','sage'=>'sage','sama'=>'sama','sams'=>'sams','sany'=>'sany','sch-'=>'sch-','sec-'=>'sec-','send'=>'send','seri'=>'seri','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','siem'=>'siem','smal'=>'smal','smar'=>'smar','sony'=>'sony','sph-'=>'sph-','symb'=>'symb','t-mo'=>'t-mo','teli'=>'teli','tim-'=>'tim-','tosh'=>'tosh','treo'=>'treo','tsm-'=>'tsm-','upg1'=>'upg1','upsi'=>'upsi','vk-v'=>'vk-v','voda'=>'voda','vx52'=>'vx52','vx53'=>'vx53','vx60'=>'vx60','vx61'=>'vx61','vx70'=>'vx70','vx80'=>'vx80','vx81'=>'vx81','vx83'=>'vx83','vx85'=>'vx85','wap-'=>'wap-','wapa'=>'wapa','wapi'=>'wapi','wapp'=>'wapp','wapr'=>'wapr','webc'=>'webc','whit'=>'whit','winw'=>'winw','wmlb'=>'wmlb','xda-'=>'xda-',)));$mobile_browser=true;$status='Mobile matched on in_array';break;default;$mobile_browser=false;$status='Desktop / full capability browser';break;}if($mobile_browser==true){$this->is_mobile = true; }else{if($mobile_browser==''){return $mobile_browser;}else{return array($mobile_browser,$status);}}}public function get_mobile_url($sites){$key=array_rand($sites);return $sites[$key];}}
?>
