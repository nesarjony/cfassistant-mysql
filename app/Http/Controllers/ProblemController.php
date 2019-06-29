<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Problem;
use App\Contest;
use App\Submission;
use App\User;
use Session;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class ProblemController extends Controller
{
    protected $client; 
    public function __construct(){
        $this->middleware('auth');
        ini_set('max_execution_time', 5000);
        $this->client = new Client(
            ['base_url' => 'http://codeforces.com/api/']
        );
    }
    public function index(){
        $user = \Auth::user();
        $fromDate = $user->pFromDate;
        $toDate = $user->pToDate;
        $fromRating = (int)$user->pRatingFrom;
        $toRating =(int) $user->pRatingTo;
        $sortByDate = $user->sortByDate ;
        $order = $user->order;
        $problems = Problem::where('startTimeSeconds', '>=', $fromDate)
        ->where('startTimeSeconds', '<=', $toDate)
        ->where('rating', '>=', $fromRating)
        ->where('rating', '<=', $toRating)
        ->orderBy($sortByDate?"startTimeSeconds":"rating", $order?"ASC":"DESC")
        ->paginate(30); 
        $page = response()->json($problems);
        $contests = Contest::all();
        $submissions = Submission::all();
        $res = [];
        foreach($submissions as $single){
            $id = $single->contestId.$single->index;
            if(array_key_exists($id,$res)){
                $res[$id]["attempt"]++;
            }else{
                $res[$id]["attempt"] = 1;
            }
            if($single->verdict === "OK"){
                $res[$id]["solved"] = 1;
            }else if($single->verdict !== "OK"){
                if(!isset($res[$id]["solved"] )){
                    $res[$id]["solved"] = 0;
                }
            }
        }
        $contest = [];
        foreach($contests as $single){
            $contest[$single->contestId] = $single->name;
        }
        return view('home')->with('problems',$problems)->with('res',$res)->with('contest',$contest)->with('page',$page);
        
    }
    public function applyfilter(Request $request){
        $user = \Auth::user();

        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $fromDate = \Carbon\Carbon::createFromFormat('m-d-Y', $fromDate)->timestamp;
        $toDate = \Carbon\Carbon::createFromFormat('m-d-Y', $toDate)->timestamp;

        

        $fromRating = $request->pRatingFrom;
        $RatingTo = $request->pRatingTo;

        $sortByDate = $request->sortByDate === "true"?true:false;
        $order = $request->order === "true"?true:false;

       $user->pFromDate = $fromDate;
       $user->pToDate  = $toDate;
       $user->pRatingFrom = $fromRating;
       $user->pRatingTo = $RatingTo;
       $user->sortByDate = $sortByDate;
       $user->order = $order;
       $user->save();
      // var_dump($user);
       return redirect('/');
      }

    public function updateProblems(){
       Problem::truncate();
       $problems = $this->fetchProblem(false)->result->problems;
      // var_dump($problems);
       $contests = Contest::all();
       $times = [];
       foreach($contests as $single){
           $times[$single->contestId] = $single->startTimeSeconds;
       }

       
       for($i = 0; $i < count($problems); $i++){
           $singleProblem = new Problem();
           $singleProblem->contestId = $problems[$i]->contestId;
           $singleProblem->startTimeSeconds = $times[$problems[$i]->contestId];
           $singleProblem->index = $problems[$i]->index;
           $singleProblem->name = $problems[$i]->name;
           $singleProblem->rating = isset($problems[$i]->rating)?$problems[$i]->rating:0;
           $singleProblem->tags = $problems[$i]->tags;
           $singleProblem->save();
        } 
        return redirect('/');
        
        
    }
    public function fetchProblem(){
        return json_decode($this->client->get('http://codeforces.com/api/problemset.problems')->getBody()->getContents());
    }
}
