<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Submission;
use App\Zahin;
use App\Problem;
use App\Contest;
use Session;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class ZahinController extends Controller
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
        $problems = Zahin::where('rating', '>=', 1)
        ->where('rating', '<=', 5000)
        ->paginate(30);
        $problemsAll = Zahin::all()->where('rating', '>=', 1400)
        ->where('rating', '<=', 5000);
        $unique = [];
        foreach($problemsAll as $single){
            $id = $single->contestId.$single->index;
            if(!isset($unique[$id])) $unique[$id]=1;
        }
        $total = count($unique); 
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
                if(isset($unique[$id]))
                    $unique[$id] = 2;
            }else if($single->verdict !== "OK"){
                if(!isset($res[$id]["solved"] )){
                    $res[$id]["solved"] = 0;
                }
                if(isset($unique[$id]) && $unique[$id] !=2)
                    $unique[$id] = 3;
            }
        }
        $ans = 0;
        foreach($unique as $r => $value){
           $ans += ($value == 3);
           $ans += ($value == 1);
        }
        //var_dump($ans);
        $contest = [];
        foreach($contests as $single){
            $contest[$single->contestId] = $single->name;
        }
       return view('zahin')->with('problems',$problems)->with('res',$res)->with('contest',$contest)->with('total',$ans);
        
    }
    public function update(){
       Zahin::truncate();
       $user = "sgtlaugh";
       $submissions = $this->fetchSubmission($user)->result;
       $allProblems = Problem::all();
       $rating = [];
       foreach($allProblems as $single){
           $id = $single->contestId.$single->index;
           $rating[$id] = $single->rating;
       }

       for($i = 0; $i < count($submissions); $i++){
           $singleSubmission = new Zahin();
           $singleSubmission->id = $submissions[$i]->id;
           $singleSubmission->contestId = $submissions[$i]->contestId;
           $singleSubmission->creationTimeSeconds = $submissions[$i]->creationTimeSeconds;
           $singleSubmission->index = $submissions[$i]->problem->index;
           $singleSubmission->name = $submissions[$i]->problem->name;
           $ID = $singleSubmission->contestId.$singleSubmission->index;
           $singleSubmission->rating = isset($rating[$ID]) ? $rating[$ID] : 1500;
           $singleSubmission->participantType = isset($submissions[$i]->author->participantType)?$submissions[$i]->author->participantType:"";
           $singleSubmission->programmingLanguage = $submissions[$i]->programmingLanguage;
           $singleSubmission->verdict = isset($submissions[$i]->verdict)?$submissions[$i]->verdict:"Not Attempt";
           $singleSubmission->timeConsumedMillis = $submissions[$i]->timeConsumedMillis;
           $singleSubmission->memoryConsumedBytes = $submissions[$i]->memoryConsumedBytes;
           $singleSubmission->save();
        }
        return redirect('/');
        
    }
    public function fetchSubmission($user){
        return json_decode($this->client->get('https://codeforces.com/api/user.status',[
            'query' => ['handle' => $user],
        ]
        )->getBody()->getContents());
    }
}
