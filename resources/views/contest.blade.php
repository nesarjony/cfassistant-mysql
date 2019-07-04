@extends('layouts.app')

@section('content')
<div class="container">
    <!--
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>  
    </div>
--> 
<div class="row">
    <div class="col-md-12">
    
    <div id="filter-panel" class="collapse filter-panel">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form class="form-inline" role="form" method="POST" action="{{url('contest/apply')}}">
                            @csrf
                            <?php 
                                $user =  Auth::user();
                            ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>From Date: </label>
                                    <div id="datepicker" class="input-group date" data-date-format="mm-dd-yyyy">
                                        <input class="form-control" type="text" name="fromDate" readonly value="{{date('m-d-Y',$user->cFromDate) }}" />
                                        <span class="input-group-addon"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>To Date: &nbsp; </label>
                                    <div id="datepicker2" class="input-group date" data-date-format="mm-dd-yyyy">
                                        <input class="form-control" type="text" name="toDate" value="{{ date('m-d-Y',$user->cToDate) }}" readonly />
                                        <span class="input-group-addon"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="gym"  value="{{ old('gym') }}" id="gym">
                                    <label>GYM </label>
                                    <br>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success filter-col">
                                    <span class="glyphicon glyphicon-record"></span> Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
</div>

</div>
<div class="col-md-12">
    <button type="button" class="btn btn-success" data-toggle="collapse" data-target="#filter-panel">
            <span class="glyphicon glyphicon-cog"></span> <div class="fa fa-gear"></div>
        </button>
</div>
    <div class="col-md-12">
        <div class="panel panel-default panel-problems">
            <div class="panel-heading">
              <h3 class="panel-title">Contest</h3></div>
            <div class="table-responsive">
                <table data-reload="no" id="table" class="table table-standings">
                    <thead>
                        <tr>
                            <th rowspan="2" style="vertical-align: middle;">#</th>
                            <th rowspan="2" style="vertical-align: middle;width: 250px;">Name</th>
                            <th rowspan="2" style="width: 64px;"></th>
                            <th rowspan="2" style="width: 24px;"></th>
                            
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
                    @foreach ($contests as $contest)
         
                        <?php 
                             $problems = DB::table('problems')->where('contestId',$contest->contestId)->orderBy('index','ASC') ->get();
                             if(count($problems) < 1) continue;
                        ?>
                        <tr data-id="">
                            <td>{{($page*30)+$id}}</td>
                            <?php $id++ ?>
                            <?php $link = "https://codeforces.com/contest/";
                            $link .= $contest->contestId;
                            $url = url($link);
                            ?>
                            <td><a  target="_blank" href="{{$url}}"> {{$contest->name}} </a></td>
                            <td>
                                <a href="">
                                    <div class="label label-info">6</div>
                                    <div class="label label-default">134</div>
                                </a>
                            </td>
                            <td style="width: 24px;"></td>
                            @for($i = 0; $i < count($problems) && $i < 10;$i++)
                            <?php $problem = $problems[$i]; ?>
                            <td>
                                <?php 
                                    $index = $problem->index;
                                    $rating = $problem->rating;
                                 ?>
                                <a target="_blank" href="{{$url}}/problem/{{$index}}">
                                    <?php
                                     $problem = $contest->contestId.$index;
                                    ?>
                                 
                                    @if (isset($res[$problem]['solved']) && $res[$problem]['solved'] === 1)
                                    <div class="label label-success">
                                    {{ isset($res[$problem]['rating']) ? $res[$problem]['rating']: '-'}}
                                    </div>
                                    @else
                                    <div class="label label-warning">
                                    
                                        {{ isset($res[$problem]['rating']) ? $res[$problem]['rating']: '-'}}
                    
                                    </div>
                                    @endif
                                    <div class="label label-default">{{$index}} &nbsp; ({{ isset($res[$problem]['attempt']) ? $res[$problem]['attempt']: '0'}})</div>
                                </a>
                            </td>
                             @endfor
                            
                        </tr>
                        
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
         {{$contests->links()}}
    </div>
   
</div>

</div>
@endsection
