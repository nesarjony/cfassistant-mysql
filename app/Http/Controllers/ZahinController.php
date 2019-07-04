<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Submission;
use App\Zahin;
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
        $problems = Zahin::paginate(30); 
        //$page = response()->json($problems);
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
        return view('zahin')->with('problems',$problems)->with('res',$res)->with('contest',$contest);
        
    }
    public function update(){
       Zahin::truncate();
       $user = "sgtlaugh";
       $submissions = $this->fetchSubmission($user)->result;

       for($i = 0; $i < count($submissions); $i++){
           $singleSubmission = new Zahin();
           $singleSubmission->id = $submissions[$i]->id;
           $singleSubmission->contestId = $submissions[$i]->contestId;
           $singleSubmission->creationTimeSeconds = $submissions[$i]->creationTimeSeconds;
           $singleSubmission->index = $submissions[$i]->problem->index;
           $singleSubmission->name = $submissions[$i]->problem->name;
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
