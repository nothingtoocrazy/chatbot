<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\League;

class LeagueController extends Controller
{
    /** 
     * get request to display all leagues of given type 
 
     * @param $league the league that we will get from the user to display (e.g. Premier, Bundasliga) 
     * */
       
    public function league($league = null) {
      $results = League::all();
      return $results;
    }
}
