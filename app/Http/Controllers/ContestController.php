<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contest;
use App\Submission;
use App\Problem;
use App\User;
use Session;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class ContestController extends Controller
{
    protected $client;
    public function __construct()
    {
        $this->middleware('auth');
        ini_set('max_execution_time', 5000);
    }
    public function index(){
        $user = \Auth::user();
        $fromDate = $user->cFromDate;
        $toDate  = $user->cToDate;
        $gym = $user->cGym;
        $contests = Contest::where('startTimeSeconds', '>=', $fromDate)
        ->where('startTimeSeconds', '<=', $toDate)
        ->paginate(30); 
        
        $submissions = Submission::all();
        $problems = Problem::all();
        
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
        foreach($problems as $single){
            $id = $single->contestId.$single->index;
            $res[$id]['rating'] = $single->rating;
        }

        return view('contest')->with('contests',$contests)->with('res',$res);
    }
    public function applyfilter(Request $request){
      $user = \Auth::user();

      $fromDate = $request->fromDate;
      $toDate = $request->toDate;
      $fromDate = \Carbon\Carbon::createFromFormat('m-d-Y', $fromDate)->timestamp;
      $toDate = \Carbon\Carbon::createFromFormat('m-d-Y', $toDate)->timestamp;
      
      $user->cFromDate = $fromDate;
      $user->cToDate  = $toDate;
      
      if ( ! $request->has('gym')) {
        $user->cGym  = true;
      }else{
        $user->cGym  = false;
      }
      
        $user->save();
        return redirect('/contest');
     /*
      
      $contests = Contest::where('startTimeSeconds', '>=', $fromDate)
      ->where('startTimeSeconds', '<=', $toDate)->paginate(30); 
     // $contests = Contest::paginate(30); 
      $submissions = Submission::all();
      $problems = Problem::all();
    */
    }
    public function updateContest(){
       Contest::truncate();
       $contest = $this->fetchContest(false)->result;
       for($i = 0; $i < count($contest); $i++){
           $singleContest = new Contest();
           $singleContest->contestId = $contest[$i]->id;
           $singleContest->name = $contest[$i]->name;
           $singleContest->phase = $contest[$i]->phase;
           $singleContest->durationSeconds = $contest[$i]->durationSeconds;
           $singleContest->startTimeSeconds = $contest[$i]->startTimeSeconds;
           $singleContest->save();
        }
        return redirect('/contest');
        
    }
    public function fetchContest($gym = false){
        $this->client = new Client(
            ['base_url' => 'http://codeforces.com/api/']
        );
        return json_decode($this->client->get('http://codeforces.com/api/contest.list', [
            'query' => ['gym' => $gym],
        ])->getBody()->getContents());
    }
}
