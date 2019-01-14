<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BackProcess;
use App\IpStatus;
use App\UrlStatus;
use DB;
use App\Links;

class BackController extends Controller
{
	 public function file_get_contents_curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
	 public function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city"           => @$ipdat->geoplugin_city,
                            "state"          => @$ipdat->geoplugin_regionName,
                            "country"        => @$ipdat->geoplugin_countryName,
                            "country_code"   => @$ipdat->geoplugin_countryCode,
                            "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }
	public function url_test($url) {
        $timeout = 2;
        $ch = curl_init();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
        $http_respond = curl_exec($ch);
        $http_respond = trim( strip_tags( $http_respond ) );
        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
        return $http_code;
        curl_close( $ch );
    }
   public function Get_Process_data (){
	    DB::select("delete from backprocess where status='1' ");
		$data1=DB::table('backprocess')->where('status','0') ->limit(100)->get();
		foreach($data1 as $td){
				$urls = links::select('url','id','url')->where('id',$td->link_id)->get();
				foreach($urls as $dt){
					$html = $this->file_get_contents_curl($dt['url']);
					if(isset($html) && $html !='' && $html !=null){
						$doc = new \DOMDocument();
						@$doc->loadHTML($html);
						$nodes = $doc->getElementsByTagName('title');
						if(isset($nodes->item(0)->nodeValue) && $nodes !='' && $nodes !=null){
							$title = $nodes->item(0)->nodeValue;
						}
						$metas = $doc->getElementsByTagName('meta');

						for ($i = 0; $i < $metas->length; $i++)
						{
							$meta = $metas->item($i);
							if($meta->getAttribute('name') == 'description')
								$description = $meta->getAttribute('content');
						}
					}
						if(isset($title )&& $title !==''){
							  $title_val = $title;
						}else{
						$title_val = 'NA';
						}
						if(isset($description) && $description !=='' ){
						$description_val =  $description;
							  
						}else{
							$description_val = 'NA';
						}	
					$data = array('title' => $title_val,
						'description'=> $description_val,
						);
						
				   DB::table('links')->where(['id' => $dt['id']])->update($data);
				   DB::table('backprocess')->where(['link_id' => $dt['id']])->update(['status' => '1']);
				}
			
		}
	}
	public function Get_IP_data(){
		 DB::select("delete from BackIpStatus where status='1' ");
		$data1=DB::table('BackIpStatus')->where('status','0') ->limit(100)->get();
		foreach($data1 as $td){
			$urls = links::select('ip','id','url')->where('id',$td->link_id)->get();
			foreach($urls as $data){
            $return=$this->ip_info($data['ip'], "Location");
            if($return['country']=='China' || $return['country']=='china') {
                DB::table('links')->where(['id' => $data['id']])->update(array('china_ip' => '1'));
            }else{
                DB::table('links')->where(['id' => $data['id']])->update(array('china_ip' => '0'));
            }
            $ip=gethostbyname(parse_url($data['url'], PHP_URL_HOST));
            $return=$this->ip_info($ip, "Location");
			
            if($return['country']=='China' || $return['country']=='china') {
                DB::table('links')->where(['id' => $data['id']])->update(array('ip'=>$ip,'china_ip' => '1','ipregion'=>$return['country']));
            }else{
				if(isset($return['country']) && $return['country'] !=''){
					DB::table('links')->where(['id' => $data['id']])->update(array('ip'=>$ip,'china_ip' => '0','ipregion'=>$return['country']));
				}else{
					DB::table('links')->where(['id' => $data['id']])->update(array('ip'=>$ip,'china_ip' => '0','ipregion'=>'NA'));
				}
               // DB::table('links')->where(['id' => $data['id']])->update(array('ip'=>$ip,'china_ip' => '0','ipregion'=>$return['country']));
            }
			}
			 DB::table('BackIpStatus')->where(['link_id' => $data['id']])->update(['status' => '1']);
        }
		
	}
	public function Get_Url_data(){
		DB::select("delete from BackUrlStatus where status='1' ");
		$data1=DB::table('BackUrlStatus')->where('status','0') ->limit(100)->get();
		foreach($data1 as $td){
			$url = links::select('url','id','url_type')->where('id',$td->link_id)->get();
			foreach($url as $data){
				$result=array();
				if($data['url_type']=='http') {
					$code = $this->url_test(str_replace('http','https',$data['url']));
					//return response()->json($code);
					$status=($code=='200' || $code=='302')?'1':'0';
					$code1=$this->url_test($data['url']);
					$status1=($code1=='200' || $code1=='302')?'1':'0';
					$result=array('site_status' => $code1,'http_status'=>$status1,'https_status' => $status);
				}
				else if($data['url_type']=='https') {
					$code = $this->url_test(str_replace('https','http',$data['url']));
					$status=($code=='200' || $code=='302')?'1':'0';
					$code1=$this->url_test($data['url']);
					$status1=($code1=='200' || $code1=='302')?'1':'0';
					$result=array('site_status' => $code1,'https_status'=>$status1,'http_status' => $status);
				}
				DB::table('links')->where(['id' => $data['id']])->update($result);
				DB::table('BackUrlStatus')->where(['link_id' => $data['id']])->update(['status' => '1']);
			}
		} 
		
	}
	
	
	   
}

