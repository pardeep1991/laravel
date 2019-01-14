@extends('layouts.AdminLayout')
@section('content')
        <div class="card-header">
            <div>
                <ul class="navbar-nav ml-auto" style="display: inline-block;">
                    <li class="nav-item" style="padding: 1px;float: left;">
                        <a href="javascript:;" class="btn btn-info btn-sm" onclick="new_check();">Update Url Status</a>
                    </li>
                    <li class="nav-item" style="padding: 1px;float: left;">
                        <a href="javascript:;"  class="btn btn-info btn-sm" onclick="check_ip();">Update IP </a>
                    </li>
					<li class="nav-item" style="padding: 1px;float: left;">
                        <a href="javascript:;" onclick="Check_Title();"  class="btn btn-info btn-sm" >Check Meta</a>
                    </li>
					 <li class="nav-item" style="padding: 1px;float: left;">
                        <a href="{{url('Check-JCLIB')}}"  class="btn btn-info btn-sm" >Check JC Library</a>
                    </li>
					<!--<li class="nav-item" style="padding: 1px;float: left;">
                        <a href="javascript:;" class="btn btn-info btn-sm" onclick="new_check_background();">Update Url Status In Background 
						<span class="percenatge_div"></span>
						</a>
                    </li> -->
					
                </ul>
            </div>
        </div>

     <div class="card-body" style="padding: 0px;">
            <div class="table-responsive" id="hide_search">
			    <table class="table table-bordered" id="new_example" width="100%" cellspacing="0">
                   <tr>
                        <form action="{{route('urllist')}}" method="get">
                            {{ csrf_field() }}
                            <th><input type="checkbox" id="checkAll" value="1"></th>
                            <th><input class="search" type="search" name="search_data" placeholder="Datasource" value="{{ !empty($search_data) ? $search_data :'' }}"></th>
                            <th><input class="search" type="search" name="search_url" placeholder="Url" value="{{ !empty($search_url) ? $search_url :'' }}"></th>
                            <th><input class="search" type="search" name="search_title" placeholder="Title" value="{{ !empty($search_title) ? $search_title :'' }}"></th>
                            <th class="customers">
                                <select name="china_ip" value="{{ !empty($china_ip) ? $china_ip :'' }}" id="china_ip">
                                    <option value="">All</option>
                                    <option {{ (!empty($china_ip) && $china_ip=='1') ? 'selected' :'' }} value="1">In China</option>
                                    <option {{ (isset($china_ip) && $china_ip != '' && $china_ip=='0') ? 'selected' :'' }} value="0">Not In China</option>
                                    <option {{ (!empty($china_ip) && $china_ip=='-1') ? 'selected' :'' }} value="-1">Unknown</option>
                                </select>
                            </th>
                            <th class="customers">
                                <select name="url_type" value="{{ !empty($url_type) ? $url_type :'' }}" id="url_type">
                                    <option value="">All</option>
                                    <option {{ (!empty($url_type) && $url_type=='http') ? 'selected' :'' }} value="http">Http</option>
                                    <option {{ (!empty($url_type) && $url_type=='https') ? 'selected' :'' }} value="https">Https</option>
                                </select>
                            </th>
                            <th class="customers">
                                <select name="category" value="{{ !empty($category) ? $category :'' }}"  id="category">
                                    <option value="">All</option>
                                    @foreach($catdata as $ctd)
                                        <option {{ (!empty($category) && $category==$ctd->id) ? 'selected' :'' }} value="{{$ctd->id}}">{{$ctd->title}}</option>
                                    @endforeach
                                </select>

                            </th>
                            <th class="customers">
                                <select name="review_check" value="" value="{{ !empty($review_check) ? $review_check :'' }}" id="review_check">
                                    <option value="">All</option>
                                    <option {{ (!empty($review_check) && $review_check=='1') ? 'selected' :'' }} value="1">Reviewed</option>
                                    <option {{ (!empty($review_check) && $review_check=='-1') ? 'selected' :'' }} value="-1">No Review</option>
                                </select>
                            </th>
                            <th class="customers">
                                <select name="audit_check" value="{{ !empty($audit_check) ? $audit_check :'' }}" id="audit">
                                    <option value="">All</option>
                                    <option {{ (!empty($audit_check) && $audit_check=='1') ? 'selected' :'' }} value="1">Yes</option>
                                    <option {{ (!empty($audit_check) && $audit_check=='0') ? 'selected' :'' }} value="0">No</option>
                                    <option {{ (!empty($audit_check) && $audit_check=='-1') ? 'selected' :'' }} value="-1">Unknown</option>
                                </select>
                            </th>
                            <th class="customers">
                                    <select name="JCLIB" value="{{ !empty($JCLIB) ? $JCLIB :'' }}">
                                        <option value="">All</option>
                                        <option {{ (isset($JCLIB) && $JCLIB=='1') ? 'selected' :'' }} value="1">Yes</option>
                                        <option {{ (isset($JCLIB) && $JCLIB=='0') ? 'selected' :'' }} value="0">No</option>
                                        <option {{ (isset($JCLIB) && $JCLIB=='-1') ? 'selected' :'' }} value="-1">Unknown</option>
                                    </select>
                            </th>
                            <th class="customers">
                                <select name="site_status" value="{{ !empty($site_status) ? $site_status :'' }}">
                                    <option value="">All</option>
                                    <option {{ (!empty($site_status) && $site_status=='1') ? 'selected' :'' }} value="1">Yes</option>
                                    <option {{ (!empty($site_status) && $site_status=='-1') ? 'selected' :'' }} value="-1">Unknown</option>
                                </select>
                            </th>

                            <th class="customers">
                                <select name="http_status" value="{{ !empty($http_status) ? $http_status :'' }}" id="http_status">
                                    <option value="">All</option>
                                    <option  {{ (!empty($http_status) && $http_status=='1') ? 'selected' :'' }} value="1">Yes</option>
                                    <option   {{ (!empty($http_status) && $http_status=='0') ? 'selected' :'' }} value="0">No</option>
                                    <option   {{ (!empty($http_status) && $http_status=='-1') ? 'selected' :'' }} value="-1">Unknown</option>
                                </select>
                            </th>
                            <th class="customers">
                                <select name="https_status" value="{{ !empty($https_status) ? $https_status :'' }}" id="https_status">
                                    <option value="">All</option>
                                    <option {{ (!empty($https_status) && $https_status=='1') ? 'selected' :'' }} value="1">Yes</option>
                                    <option  {{ (!empty($https_status) && $https_status=='0') ? 'selected' :'' }} value="0">No</option>
                                    <option  {{ (!empty($https_status) && $https_status=='-1') ? 'selected' :'' }} value="-1">Unknown</option>
                                </select>
                            </th>
                            <th>
                                <input type="submit" name="sabmit" value="Filter" class="btn btn-default btn-sm">
                            </th>

                        </form>
                    </tr>


                    <thead>
                    <tr>
                        <th> Id</th>
                        <th>Datasource</th>
                        <th>Url</th>
                        <th>Title</th>
                        <th>China</th>
                        <th>Protocol</th>
                        <th>Category</th>
                        <th>Review Date</th>
                        <th>Audit Status</th>
                        <th>JCLIB</th>
                        <th>Status</th>

                        <th>Http</th>
                        <th>Https</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(session()->has('updatedurl'))
                        <div class="alert alert-error">
                             Total Updated Url : {{ session()->get('updatedurl') }}
                        </div>
                    @endif
                    @if(session()->has('inserted'))
                        <div class="alert alert-success">
                            Successfully Add Links : {{ session()->get('inserted') }}
                        </div>
                    @endif
                    @foreach($data as $idt => $dt)
                           <tr>
                               <td>
                                   <input type="checkbox" name="check_select[]" class="yourClass" value="{{$dt->id}}">
                                   {{ $data->firstItem() + $idt}}
                               </td>
                               <td>{{ $dt->datasource}}</td>
                               <td><a href="javascritp:;" onclick="open_url(this,'{{$dt->url}}')">{{   substr($dt->url, 0, 25) }}</a></td>
                               <td>
                               @if($dt->title == '' && $dt->title == null) Unknown
                                @else{{
                                mb_substr($dt->title,0,10, "utf-8")
                                }}
                                   @endif
                               </td>
                                 @if($dt->china_ip == '1')<td class="status_yes"> Yes</td>
                                   @elseif($dt->china_ip == '0')<td class="status_no"> No </td>
                                   @elseif($dt->china_ip == '-1')<td class=""> Unknown  </td>
                                   @endif

                                <td>{{$dt->url_type}}</td>
                               <td>
                                   <select name="category"  class="form-control nopadding" onchange="ChangeCategory(this,{{$dt->id}});">
                                       @foreach($catdata as $ct)
                                           <option @if($dt->category == $ct->id) selected @endif value="{{ $ct->id }}" id="{{$dt->id}}">{{ $ct->title }}</option>
                                       @endforeach
                                   </select>
                               </td>

                               @if(isset($dt->review) && $dt->review!=='-1')
                                   <td class="status_yes" id="review{{$dt->id}}" onclick="Change_review(this,'links','review',{{$dt->id}});"> {{$dt->review}}</td>
                               @else
                                   <td id="review{{$dt->id}}" class="status_no" onclick="Change_review(this,{{$dt->id}});" >No Review </td>
                                   @endif


                               <td>
                                   <a  href="javascript:;"  onclick="changeaudit_new(this,'links','audit',{{$dt->id}});" class=" @if ($dt->audit==1) {{'status_yes'}} @else {{'status_no'}} @endif ">@if ($dt->audit==1) {{'Yes'}} @elseif($dt->audit==-1){{'Unknown'}} @else{{'No'}} @endif</a>
                               </td>
                               <td>
                                  @if( $dt->JCLIB == -1)
                                       Unknown
                                   @elseif($dt->JCLIB == 0)
                                       No
                                   @elseif($dt->JCLIB == 1)
                                      Yes
                                   @endif
                               </td>
                                    @if($dt->site_status == '-1')<td>Unknown</td>
                                        @else<td>{{$dt->site_status}}</td>
                                    @endif

                                    @if($dt->http_status == '1')<td class="status_yes"> Yes</td>
                                    @elseif($dt->http_status == '0')<td class="status_no"> No </td>
                                    @elseif($dt->http_status == '-1')<td class=""> Unknown </td>
                                    @endif

                                     @if($dt->https_status == '1')<td class="status_yes"> Yes</td>
                                    @elseif($dt->https_status == '0')<td class="status_no"> No </td>
                                    @elseif($dt->https_status == '-1')<td class=""> Unknown </td>
                                    @endif
                                   <td>
                                       <div class="row">
                                           <div class="col-md-4">
                                           <div class="edit_form">
                                               <a href="{{route('url-list.edit',$dt->id)}}" style="color: #6d7073;">
                                                     <i class="pull-right fa fa-pencil" style="font-size:20px"></i>
                                               </a>
                                           </div>
                                           </div>
                                           <div class="col-md-4">
                                           <div class="Link_btn">
                                               <form action="{{route('url-list.destroy',$dt->id)}}" method="POST">
                                                                @csrf
                                                                @method('delete')
                                                       <button type="submit" class="bgnone">
                                                           <i class=" fa fa-trash-o" style="font-size:20px"></i>
                                                       </button>
                                               </form>
                                           </div>
                                           </div>
                                       </div>

                                   </td>
                           </tr>
                        @endforeach

                    </tbody>
                </table>
                {{ $data->appends(['search_data' => $search_data,'search_url' => $search_url,'search_title' => $search_title,'china_ip'=> $china_ip,'url_type'=>$url_type,'review_check'=>$review_check,'category'=>$category,'audit_check'=>$audit_check,'JCLIB'=>$JCLIB,'site_status'=>$site_status,'http_status'=>$http_status,'https_status'=>$https_status])->links() }}
            </div>

        </div>
    

@endsection