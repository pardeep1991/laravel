<?php

namespace App\Http\Controllers;
use Auth;
use App\Mylibs\Common;
use App\Category;
use App\BackProcess;
use App\IpStatus;
use App\UrlStatus;
use App\Links;
use App\Tags;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Excel;
use DB;
use Session;
use App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\App;

class DashboardController extends Controller
{
    public $duplicates = [];
    public $inserted = [];
    public $updatedurl = [];
    function __construct() {
    }

    public function index(){
        $links = Links::get()->count();
        $cat = Category::get()->count();
        return view('dashboard/HomePage',compact('links','cat'));
    }
    public function changeFlag(Request $request){
        $get=DB::table($request->table)->where('id',$request->id)->pluck($request->column)->first();
        if($get==1){
            DB::table($request->table)->where('id',$request->id)->update([$request->column=>0]);
            return response()->json(['status'=>0]);
        }else{
            DB::table($request->table)->where('id',$request->id)->update([$request->column=>1]);
            return response()->json(['status'=>1]);
        }
    }
    public function csv_view(){
        return view('admin/csv/create');
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
    public function checkIp(Request $request){
		if(isset($request->selectall) && $request->selectall=='1'){
			$da=array();
			$ides=\Session()->get('ids');
			foreach($ides as $id)
				$da[]=$id['id'];
		}else
		
        $da=json_decode($request->select);
        $url = links::select('ip','id','url')->whereIn('id',$da)->get();
		foreach($url as $ids){
			$data = new IpStatus();
			$data['link_id']= $ids['id'];
			$data['status']= '0';
			$data->save();
		}
		 return response()->json('checking');
		 $url = links::select('ip','id','url')->whereIn('id',$da)->get();
        foreach($url as $data){
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
                DB::table('links')->where(['id' => $data['id']])->update(array('ip'=>$ip,'china_ip' => '0','ipregion'=>$return['country']));
            }



        }
        return response()->json('Done');
    }
	
	
	
	
    /*check title and discription*/

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
    public function CheckTitle(Request $request){
		if(isset($request->selectall) && $request->selectall=='1'){
			$da=array();
			$ides=\Session()->get('ids');
			foreach($ides as $id)
				$da[]=$id['id'];
		}
		else   $da=json_decode($request->select);		
        $url = links::select('url','id','url')->whereIn('id',$da)->get();
		foreach($url as $ids){
			$data = new backprocess();
			$data['action_type']= '1';
			$data['link_id']= $ids['id'];
			$data['status']= '0';
			$data->save();
		}
		 return response()->json('checking backend process');
        foreach($url as $dt){ 
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
        }
        return response()->json($data);

    }
/*End get title or discription*/

    public function checkHttp(Request $request){
        $da=json_decode($request->select);
        $url = links::select('url','id','url_type')->whereIn('id',$da)->get();
        foreach($url as $data){
            if($data['url_type']=='http') {
                $code = $this->url_test(str_replace('http','https',$data['url']));
                $status=($code=='200' || $code=='302')?'1':'0';
                $result = array('https_status' => $status);
            }
            else if($data['url_type']=='https') {
                $code = $this->url_test(str_replace('https','http',$data['url']));
                $status=($code=='200' || $code=='302')?'1':'0';
                $result = array('http_status' => $status);
            }
            DB::table('links')->where(['id' => $data['id']])->update($result);
        }
        return response()->json('Done');
    }
    public function checkUrl(Request $request){
        $da=json_decode($request->select);
        $url = links::select('url','id','url_type')->whereIn('id',$da)->get();
		
        foreach($url as $data){
            $code=$this->url_test($data['url']);
            $results=array('site_status'=>$code);
            $status=($code=='200' || $code=='302')?'1':'0';
            if($data['url_type']=='http')
                $result=array_merge($results,array('http_status'=>$status));
            else if($data['url_type']=='https')
                $result=array_merge($results,array('https_status'=>$status));
            DB::table('links')->where(['id' => $data['id']])->update($result);
        }
        return response()->json('Done');
    }
    public function new_check_background_result(Request $request){
        $result=DB::table('settings')->select('percentage')->first();
        echo (int)$result->percentage;
        //echo 'percentage',$result->percentage;
    }
    public function new_check_background(Request $request){
        DB::table('settings')->update(['percentage'=>'0']);
        $da=json_decode($request->select);
        $url = links::select('url','id','url_type')->whereIn('id',$da)->get();
        $counter=count($url);
        $num=1;
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
            $percentage=($num*100)/$counter;
            DB::table('settings')->update(['percentage'=>$percentage,'is_active'=>'1']);
            $num++;
        }
        return response()->json('Done');
    }

    public function new_check(Request $request){
        $da=json_decode($request->select);
        $url = links::select('url','id','url_type')->whereIn('id',$da)->get();
		foreach($url as $ids){
			$data = new UrlStatus();
			$data['link_id']= $ids['id'];
			$data['status']= '0';
			$data->save();
		}
		 return response()->json('Checking');
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
        }
        return response()->json('Done');

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
    public function  csvToArray(Request $request){
        $cdata = Category::get(['title']);
        $Tdata = DB::table('tags')->where('title');
        if($request->hasFile('import_file')){
            Excel::load($request->file('import_file')->getRealPath(), function ($reader) use($cdata,$Tdata) {
                foreach ($reader->toArray() as $key => $row) {
                    if( isset($row['url']) && $row['url']!="") {
                        $url=$row['url'];
                        $host=(parse_url($url, PHP_URL_HOST));
                        $url_type=(parse_url($url, PHP_URL_SCHEME));
                        $segment=explode('/',(parse_url($url, PHP_URL_PATH)));
                        $url_level1 = $url_type.'://'.$host;
                        if(isset($segment[1]) && $segment[1]!=""){
                            $url_level2=$url_level1.'/'.$segment[1];
                        }else{
                            $url_level2 = 'NA';
                        }
                        if(isset($segment[2]) &&  $segment[2]!=""){
                            $url_level3=$url_level1.'/'.$segment[1].'/'.$segment[2];
                        }else{
                            $url_level3 ='NA';
                        }

                        $links_check = links::where('url', trim($row['url']))->first();
                        $category = 1;
                        $tag = '';
                        if( isset($row['tag']) && $row['tag']!="") {
                            $tag_check = Tags::where('title', trim($row['tag']))->first();
                            if($tag_check['title']){
                                $tag = $tag_check['id'];
                            } else{

                                $tag = DB::table('tags')->insertGetId(['title' => $row['tag']]);
                            }
                        }
                        if (isset($row['category']) && $row['category'] != "") {
                            $categories = Category::where('title', trim($row['category']))->first();
                            if ($categories['title']) {

                                $category = $categories['id'];
                            } else {
                                $category = (new Category())->insertGetId(['title' => $row['category'], 'status' => '1']);
                            }
                        }
                        if($links_check['url']){
                            $array = [
                                'title' => ((isset($links_check['title']) && $links_check['title']!=null && $links_check['title']!='Unknown')?$links_check['title']:$row['title']),
                                'description' =>  ((isset($links_check['description']) && $links_check['description']!=null && $links_check['description']!='Unknown')?$links_check['description']:$row['description']),
                                'ip' =>  ((isset($links_check['ip']) && $links_check['ip']!=null && $links_check['ip']!='Unknown')?$links_check['ip']:$row['ip']),
                                'ipregion' =>  ((isset($links_check['ipregion']) && $links_check['ipregion']!=null && $links_check['ipregion']!='Unknown')?$links_check['ipregion']:$row['ip_region']),
                                'datasource' =>  ((isset($links_check['datasource']) && $links_check['datasource']!=null && $links_check['datasource']!='Unknown')?$links_check['datasource']:$row['datasource']),
                                'updated_at' => date('Y-m-d H:i:s'),
                            ];
                            if (!empty($array)) {
                                $this->updatedurl[]=$row['url'];
                                $lastid = DB::table('links')->where(['url' => $row['url']])->update($array);
                            }
                        }else {
                            $data['URL'] = $row['url'];
                            $data['title'] = $row['title'];
                            $data['description'] = $row['description'];
                            $data['review'] =isset($row['review']) ? $row['review']: '-1';
                            $data['audit'] =  isset($row['audit_status']) ? $row['audit_status'] : '-1';
                            $data['ip'] = isset($row['ip']) ? $row['ip'] : '-1';
                            $data['ipregion'] = isset($row['ip_region']) ? $row['ip_region'] : '-1';
                            $data['category'] = $category;
                            $data['datasource'] = $row['datasource'];
                            $data['china_ip'] = isset($row['china_or_not']) ? $row['china_or_not'] : '-1';
                            $data['url_type'] = $url_type;
                            $data['domain'] = $host;
                            $data['url_level1'] =$url_level1;
                            $data['url_level2'] = $url_level2;
                            $data['url_level3'] = $url_level3;
                            $data['created_at'] = date('Y-m-d H:i:s');
                            $data['updated_at'] = date('Y-m-d H:i:s');
                            if (!empty($data)) {
                                $this->inserted[] = $row['url'];
                                $lastid = DB::table('links')->insertGetId($data);

                                if(isset($tag) && $tag!=""){
                                    DB::table('selectedtag')->insert(['link_id' => $lastid, 'tag_id' => $tag]);
                                }
                            }
                        }
                    }
                }


            });

            return redirect('url-list')->with('updatedurl',count($this->updatedurl))->with('inserted',count($this->inserted));

        }

    }
    public function ChangeCategory(Request $request){

        $userId = Auth::user()->id;
        $categoryId = $request->id;
        $linkId = $request->linkid;
        $data  = Links::find($linkId);
        $data->category = $request->id;
        $data->review = date('Y-m-d');
        $data->review_by = $userId;
        $data->save();
        if(!empty($data)) {
            return response()->json($data);
        }
    }
    public function linkdetail(Request $request){
        $id =  $request->id;
        $data = Links::find($id);
        if(!empty($data)) {
            return response()->json($data);
        }

    }

    public function csvToexport(){
        //$data =\app('exportdata');
        //print_r($data);die;
        $sessIds = \Session()->get('ids');
        $sessIds = array_column($sessIds,'id');
        $table =  Links::leftjoin('category','category.id','=','links.category')->whereIn('links.id' , $sessIds)->get(['links.url','links.title','links.description','links.review','links.category','links.ip','links.ipregion','links.datasource','links.url_type','links.url_level1','links.url_level2','links.url_level3','links.created_at','links.updated_at','category.title as category_name']);
        $filename = "URL_data.csv";
        $handle = fopen($filename, 'w+');
        fputs($handle, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
        fputcsv($handle, array('Url','Title','Description','Review','Category','IP Address','IP Region','Datasource','Url_type','Url_level1','Url_level2','Url_level3','Created At','Updated At'));

        foreach($table as $row) {
            if($row['review'] == '0'){
                $row['review'] = 'yes';
            }elseif($row['review'] == '1'){
                $row['review'] = 'No';
            }
            fputcsv($handle, array($row['url'], $row['title'], $row['description'],$row['review'],$row['category_name'], $row['ip'], $row['ipregion'], $row['datasource'],$row['url_type'],$row['url_level1'],$row['url_level2'],$row['url_level3'], $row['created_at'], $row['updated_at']));
        }
        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return Response()->download($filename, 'URL_data.csv', $headers);


    }
    public function changeaudit_new(Request $request){
        $data  = Links::find($request->id);
        $userId = Auth::user()->id;
        $data->review = date('Y-m-d');
        $data->review_by = $userId;
        $data->save();
        $date =date('Y-m-d');
        $get=DB::table($request->table)->where('id',$request->id)->pluck($request->column)->first();
        if($get==1){
            DB::table($request->table)->where('id',$request->id)->update([$request->column=>0]);
            return response()->json(['status'=>0,'date'=>$date]);
        }else{
            DB::table($request->table)->where('id',$request->id)->update([$request->column=>1]);
            return response()->json(['status'=>1,'date'=>$date]);
        }
    }
    public function Change_review(Request $request){
        $link_id = $request->id;
        $date =  date('Y-m-d');
        $data  = Links::find($request->id);
        $userId = Auth::user()->id;
        $data->review = $date;
        $data->review_by = $userId;
        $data->save();
        return response()->json(['status'=>$date]);

    }
	public function Select_All(){
		 $sessIds = \Session()->get('ids');
        $sessId = array_column($sessIds,'id');
		  return response()->json($sessId);
	}
    function Check_JCLIB(){
       // DB::table(links)update  T1 INNER JOIN links T2 on T1.url=T2.url and T2.datasource='JCLIB' SET T1.JCLIB = 1
	   //DB::select(DB::raw("update links T1 INNER JOIN links T2 on T1.url=T2.url and T2.datasource='JCLIB' SET T1.JCLIB = CASE WHEN T1.JCLIB = 1 THEN T1.JCLIB = 1 ELSE T1.JCLIB = 0 END"));
       DB::select(DB::raw("update links T1 INNER JOIN links T2 on T1.url=T2.url and T2.datasource='JCLIB' SET T1.JCLIB = 1"));
		  return redirect('urllist');
    }
}
