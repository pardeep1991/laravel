<?php

namespace App\Http\Controllers;

use App\Category;
use App\Links;
use App\Tags;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $exportdata = [];
    public $idsArray = '';
    function __construct() {
        $this->exportdata = [];
        $this->idsArray = true;
    }
    public function index(\Request $request)
    {
		$search_data = !empty($_GET['search_data']) ? $_GET['search_data'] : '';
        $search_url = !empty($_GET['search_url']) ? $_GET['search_url'] : '';
		$search_title = !empty($_GET['search_title']) ? $_GET['search_title'] : '';
		$china_ip = isset($_GET['china_ip']) ? $_GET['china_ip'] : '';
		$url_type = !empty($_GET['url_type']) ? $_GET['url_type'] : '';
        $review_check = !empty($_GET['review_check']) ? $_GET['review_check'] : '';
        $category = !empty($_GET['category']) ? $_GET['category'] : '';
        $audit_check = isset($_GET['audit_check']) ? $_GET['audit_check'] : '';
        $JCLIB = isset($_GET['JCLIB']) ? $_GET['JCLIB'] : '';
        $site_status = !empty($_GET['site_status']) ? $_GET['site_status'] : '';
        $http_status = isset($_GET['http_status']) ? $_GET['http_status'] : '';
        $https_status = isset($_GET['https_status']) ? $_GET['https_status'] : '';
        $categories = Category::pluck('id','title');
        $catdata = Category::all();

        $results = Links::where(function ($query) use($category,$search_url,$search_data,$search_title,$china_ip,$url_type,$review_check,$audit_check,$JCLIB,$site_status,$http_status,$https_status){

            if(!empty($category)) {
                $query->where(['category' => $category]);
            }
			if(!empty($search_url)){
                $query->where('url','like','%'.$search_url.'%');
			}
			if(!empty($search_data)){
				$query->where('datasource','like','%'.$search_data.'%');
			}
			if(!empty($search_title)){
				$query->where('title','like','%'.$search_title.'%');
			}
			if(isset($china_ip) && $china_ip != ''){
               $query->where(['china_ip' => $china_ip ]);
			}
			if(!empty($url_type)){
				 $query->where(['url_type' => $url_type]);
			}
			if(!empty($review_check)){
                if($review_check == '1'){
                    $query->where('review','!=','-1');
                }else{
                    $query->where(['review' => '-1']);
                }
			}
            if(isset($audit_check) && $audit_check !=''){
           
                $query->where(['audit' => $audit_check]);

            }
            if(isset($JCLIB) && $JCLIB !=''){
                $query->where(['JCLIB' => $JCLIB]);
            }
            if(!empty($site_status)){
             
                if($site_status == '1'){
                    $query->where('site_status','!=','-1');
                }else{
                    $query->where(['site_status' => '-1']);
                }
            }
            if(isset($http_status) && $http_status != ''){
               
                $query->where(['http_status' => $http_status ]);

            }if(isset($https_status) && $https_status != ''){
               
                $query->where(['https_status' => $https_status ]);

            }
        })->orderBy('id','DESC');
            \Session()->forget('ids');
            if($this->idsArray == true){
                $result = $results->get(['id']);
             
                if(count($result) > 0){
                    $this->exportdata = $result->toArray();
                    \Session()->put('ids' ,$this->exportdata);
                }
            }

        $data = $results->paginate('100');
        return view('admin/links/list',compact('data','catdata','categories','category','url_type','search_url','search_data','search_title','china_ip','review_check','audit_check','JCLIB','site_status','http_status','https_status'));
	}

    public function create()
    {
        $cat_data = Category::all();
        $tag_data = Tags::all();
        return view('admin/links/create',compact('cat_data','tag_data'));
    }

    public function store(Request $request)
    {

        $inputs = $request->all();
        $validator = Validator::make($inputs,[
            'title' => 'required'
        ]);
        if($validator->fails()){
            return redirect('links/create')->withErrors($validator)->withInput();
        }
        $data = new Links();
        $data->url = $inputs['url'];
        $data->title = $inputs['title'];
        $data->description = $inputs['description'];
        $data->category = $inputs['category'];
        $data->review = $inputs['review'];
        $data->ip = $inputs['ip'];
        $data->ipregion = $inputs['ipregion'];
        $data->datasource = $inputs['datasource'];
        $data->save();
        $id = $data->id;
        $tags = $request->get('tags');
        //print_r($tags);die;
        for($i=0;$i<count($tags);$i++){
            DB::select("insert into selectedtag set link_id=$id ,tag_id=$tags[$i]");
        }

        return redirect('url-list');
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $data = Links::find($id);
        $cat = Category::all();
        $selected=DB::table('selectedtag')->where('link_id',$id)->select('tag_id')->get();
        $data1 = Tags::all();
        $array=[];
        foreach($selected as $s)
            $array[]=$s->tag_id;
        //print_r($data1);die;
        return view('admin/links/update',compact('data','data1','array','cat'));
    }


    public function update(Request $request, $id)
    {
        $inputs = $request->all();
        $data = Links::find($id);
        $data->url = $inputs['url'];
        $data->title = $inputs['title'];
        $data->description = $inputs['description'];
        $data->category = $inputs['category'];
        //$data->review = $inputs['review'];
        $data->ip = $inputs['ip'];
        $data->ipregion = $inputs['ipregion'];
        $data->datasource = $inputs['datasource'];
        $data->save();

        DB::select("delete from selectedtag where link_id=$id ");
        $tags = $request->get('tags');
        if(!empty($tags)){
        for($i=0;$i<count($tags);$i++){
            DB::select("insert into selectedtag set link_id=$id ,tag_id=$tags[$i]");
        }
        }
        return redirect('url-list');
    }


    public function destroy($id)
    {
        $data = Links::find($id);
        $data->delete();
        return redirect('url-list');
    }

}
