@extends('layouts.app') @section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div id="filter-panel" class="collapse filter-panel">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form class="form-inline" role="form" method="POST" action="{{url('problem/apply')}}">
                            <?php 
                                $user = \Auth::user();
                            ?>
                            @csrf
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>From Date: </label>
                                    <div id="datepicker" class="input-group date" data-date-format="mm-dd-yyyy">
                                        <input class="form-control" type="text" name="fromDate" readonly value="{{date('m-d-Y',$user->pFromDate) }}" />
                                        <span class="input-group-addon"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>To Date: </label>
                                    <div id="datepicker2" class="input-group date" data-date-format="mm-dd-yyyy">
                                        <input class="form-control" type="text" name="toDate" value="{{ date('m-d-Y',$user->pToDate) }}" readonly />
                                        <span class="input-group-addon"></span>
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>From Rating: </label>
                                    <div i class="input-group " >
                                        <input class="form-control" type="text" name="pRatingFrom" value="{{ $user->pRatingFrom }}"  />
                                        <span class="input-group-addon"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>To Rating: </label>
                                    <div  class="input-group" >
                                        <input class="form-control" type="text" name="pRatingTo" value="{{ $user->pRatingTo }}"  />
                                        <span class="input-group-addon"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                
                                <div class="form-check form-radio-inline">
                                      <label>By Date: &nbsp;</label>
                                     <input type="radio" name="sortByDate" value="true" <?php if($user->sortByDate) echo "checked" ?> >
                                     <label>By Rating: &nbsp;</label>
                                     <input type="radio" name="sortByDate" value="false" <?php if(!$user->sortByDate) echo "checked" ?> >
                                </div>
                                <div class="form-check form-radio-inline">
                                     <label>ASC: &nbsp;</label>
                                     <input type="radio" name="order" value="true" <?php if($user->order) echo "checked" ?> >
                                     <label>DSC: &nbsp;</label>
                                     <input type="radio" name="order" value="false" <?php if(!$user->order)  echo "checked" ?> >
                                </div>
                                
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-success filter-col">
                                    <span class="glyphicon glyphicon-record"></span> Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-12">
            <button type="button" class="btn btn-success" data-toggle="collapse" data-target="#filter-panel">
                <span class="glyphicon glyphicon-cog"></span>
                <div class="fa fa-gear"></div>
            </button>
        </div>
        
    </div>
    <div class="row">
    <div class="col-md-12">
    <table id="" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                  <th style="text-align:center">#SL</th>
                <th>ProblemID</th>
                <th>Name</th>
                <th>Rating</th>
                <th>Contest Name</th>
                <th>Status</th>
                <th>Tags</th>
            </tr>
        </thead>
        <tbody>
            <?php 
               $id = 1; 
                //var_dump($res);
                $page = 0;
                if(isset($_GET['page'])){
                    $page = (int)$_GET['page'];
                    $page--;
                }
            ?>
            @foreach($problems as $single)
          
            <?php 
                $problemId = $single->contestId.$single->index;
                $link = "https://codeforces.com/contest/".$single->contestId."/problem/".$single->index;
                $url = url($link);
            ?>
            <tr>
                <td style="text-align:center">{{($page*30)+$id}}</td>
                <td><a href={{$url}} target="_blank">{{$single->contestId.$single->index}}</a></td>
                <td style="width:150px"><a href={{$url}} target="_blank">{{$single->name}}</a></td>
                <td>{{$single->rating}}</td>
                <td style="width:150px"><a href="https://codeforces.com/contest/{{$single->contestId}}" target="_blank">{{isset($contest[$single->contestId]) ? $contest[$single->contestId]: "-" }}</a></td>
                <td>
                    @if(isset($res[$problemId]['solved']) && $res[$problemId]['solved'])
                    <button type="button" style="color:#fff;text-align:center" class="btn btn-success">{{$res[$problemId]['attempt']}}</button>
                    @elseif(isset($res[$problemId]['attempt']) )
                    <button type="button"style="text-align:center" class="btn btn-danger">{{ $res[$problemId]['attempt'] }}</button>
                    @else
                    <button type="button" style="text-align:center" class="btn btn-warning">{{ 0 }}</button>
                    @endif
                </td>
                <td style="width:230px">
                <a class="btn btn-primary" data-toggle="collapse" href="#expand{{$id}}" id = "hide{{$id}}"  role="button" aria-expanded="false" aria-controls="collapseExample">
                    Show
                </a>
                <div class="collapse" id="expand{{$id}}">
                    <br>
                    <?php $id++ ?>
                    @foreach($single->tags as $tag)
                        <a class="trigger teal lighten-4">{{$tag}}<i class="fa fa-tag ml-2"></i></a><br>
                        @endforeach
                </div>
                
                    
                </td>
               
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="text-align:center">#SL</th>
                <th>ProblemID</th>
                <th>Name</th>
                <th>Rating</th>
                <th style="text-align:center">status</th>
                <th>Contest Name</th>
                <th>Tags</th>
            </tr>
        </tfoot>
    </table>
    
    </div>
   
    </div>
    {{ $problems->links() }}
    </div>
    @endsection