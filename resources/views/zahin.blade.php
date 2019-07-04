@extends('layouts.app') @section('content')
<div class="container">
    <div class="row">
    <div class="col-md-12">
    <table id="" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th style="text-align:center">#SL</th>
                <th>ProblemID</th>
                <th>Submission</th>
                <th>Name</th>
                <th>Contest Name</th>
                <th>My Status</th>
                <th>Verdict</th>
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
                $cnt = 0;
                $cnt = strlen((string)$single->contestId);
                
                $type = $cnt > 4 ? "gym":"contest";
                $problemId = $single->contestId.$single->index;
                $link = "https://codeforces.com/$type/".$single->contestId."/problem/".$single->index;
                $url = url($link);
                $submissionLink = "https://codeforces.com/$type/{$single->contestId}/submission/{$single->id}";
            ?>
            <tr>
                <td style="text-align:center">{{($page*30)+$id}}</td>
                <td><a href={{$url}} target="_blank">{{$single->contestId.$single->index}}</a></td>
                <td><a href={{$submissionLink}} target="_blank">{{$single->id}}</a></td>
                <td style="width:150px"><a href={{$url}} target="_blank">{{$single->name}}</a></td>

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
                <td>
                    @if($single->verdict == "OK")
                    <button type="button" style="color:#fff;text-align:center" class="btn btn-success btn-sm">AC</button>
                    @elseif($single->verdict == "WRONG_ANSWER" )
                    <button type="button"style="text-align:center" class="btn btn-danger btn-sm">WRONG ANSWER</button>
                    @else
                    <button type="button" style="text-align:center" class="btn btn-warning btn-sm">{{$single->verdict}}</button>
                    @endif
                </td>
               
               
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
            <th style="text-align:center">#SL</th>
                <th>ProblemID</th>
                <th>Submission</th>
                <th>Name</th>
                <th>Contest Name</th>
                <th>My Status</th>
                <th>Verdict</th>
            </tr>
        </tfoot>
    </table>
    
    </div>
   
    </div>
    {{ $problems->links() }}
    </div>
    @endsection