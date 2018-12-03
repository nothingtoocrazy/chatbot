<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Models\Leagues;
use App\Models\Matches;
// use GuzzleHttp\Client;

class SyncController extends Controller
{
    public function syncLeaguesToDatabase(){

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

    public function syncTeamsToDatabase(){

      $client = new \GuzzleHttp\Client();
      $headers = ['headers' => ['X-Auth-Token' => env('FOOTBALL_DATA_API_KEY')]];

      $leagues = Leagues::all();
      foreach ($leagues as $league) {
        $res = $client->get('https://api.football-data.org/v2/competitions/'.$league->competition_id.'/teams', $headers);
        $json = json_decode($res->getBody());
        foreach ($json->teams as $key => $value) {
          $team = new Teams;
          // $team->competition_id = $value->id;
          // $team->area_id = $value->area->id;
          // $team->area_name = $value->area->name;
          // $team->competition_name = $value->name;
          // $team->competition_code = $value->code;
          // TODO: LEFT OFF HERE
          $league->save();
        }
        // echo "<pre>";
        // print_r($json);
        // echo "</pre>";
      }


      // $strMessage =  "successfully inserted " . count($json->competitions) ." records into database";

      // return $strMessage;
    }

  public function syncMatchesToDatabase(){

      $client = new \GuzzleHttp\Client();
      $headers = ['headers' => ['X-Auth-Token' => env('FOOTBALL_DATA_API_KEY')]];

      // $matches = Matches::all();
        $res = $client->get('https://api.football-data.org/v2/matches', $headers);
        
        $json = json_decode($res->getBody());
        // print_r($json);
        foreach ($json->matches as $key => $value) {
        $match = new Matches;
          // $team->competition_id = $value->id;
          // $team->area_id = $value->area->id;
          // $team->area_name = $value->area->name;
          // $team->competition_name = $value->name;
          // $team->competition_code = $value->code;
          // TODO: LEFT OFF HERE $table->integer('match_id');
        $match->match_id = $value->id;
        
        $match->competition_id = $value->competition->id;
        $match->season_id = $value->season->id;

        $match->homeTeam = $value->homeTeam->name;
        $match->awayTeam = $value->awayTeam->name;

        $match->winner = $value->score->winner;
        $match->status = $value->status;

        $match->homeScore = $value->score->fullTime->homeTeam;
        $match->awayScore = $value->score->fullTime->awayTeam;
        
        $match->homePenalties = $value->score->penalties->homeTeam;
        $match->awayPenalties = $value->score->penalties->awayTeam;

        // $match->utcDate = $value->utcDate;
        // TODO: help  
        $match->save(); 

        }
        // echo "<pre>";
        // print_r($json);
        // echo "</pre>";


      $strMessage =  "successfully inserted " . count($json->matches) ." records into database";

      return $strMessage;
    }
}
