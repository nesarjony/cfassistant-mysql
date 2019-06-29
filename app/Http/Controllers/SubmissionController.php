<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Submission;
use Session;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class SubmissionController extends Controller
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
        return redirect('/');
    }
    public function updateSubmission(){
       Submission::truncate();
       $user = \Auth::user()->handle;
       $submissions = $this->fetchSubmission($user)->result;

       for($i = 0; $i < count($submissions); $i++){
           $singleSubmission = new Submission();
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
