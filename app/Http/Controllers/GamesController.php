<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Models\Leagues;
// use GuzzleHttp\Client;

class GamesController extends Controller
{
    public function upcomingGames() {
      $results = DB::select('SELECT * FROM Users ORDER BY username');
      // $resultsJSON = Response::json($results);
      // return view('welcome')->with('results', $results);
      return response()->json($results);
    }
    public function leagues(){

        //THIS WORKS
    $client = new \GuzzleHttp\Client();
    $headers = ['headers' => ['X-Auth-Token' => env('FOOTBALL_DATA_API_KEY')]];
    $res = $client->get('https://api.football-data.org/v2/competitions?plan=TIER_ONE', $headers);
    $json = json_decode($res->getBody());
    


      foreach ($json->competitions as $key => $value) {

        $league = new Leagues;
        $league->competition_id = $value->id;
        $league->area_id = $value->area->id;
        $league->area_name = $value->area->name;
        $league->competition_name = $value->name;
        $league->competition_code = $value->code;

        $league->save();
        //raw sql query that does the same thing
        // return DB::insert("INSERT INTO leagues (competition_id, area_id, area_name, competition_name, competition_code) VALUES ($value->id, $value->area->id, $value->area->name, $value->name, $value->code)");
      }
      $strMessage =  "successfully inserted " . count($json->competitions) ." records into database";

      return $strMessage;
      }

      public function getUserModel(){
        $userModel = Leagues::all();
        print_r($userModel);
        foreach($userModel as $user){
          echo $user->competition_name;
        }

      }
}
