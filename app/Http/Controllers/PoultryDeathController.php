<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PoultryBatch;
use App\Models\PoultryChickDeath;
use Illuminate\Http\Request;

class PoultryDeathController extends Controller
{
    public function deathList($batch_id)
    {
        $bathInfo = PoultryBatch::find($batch_id);
        $pageTitle = 'Death List of ' . $bathInfo->batch_name;
        $deathLists = PoultryChickDeath::where('batch_id', $batch_id)->get();
        return view('customer.death_list', compact('pageTitle', 'deathLists', 'bathInfo'));
    }


    public function store(Request $request, $id = 0)
    {
        $batch_id = $request->batch_id;
        $date_of_death = $request->date_of_death;
        $total_deaths = $request->total_deaths;
        $cause_of_death = $request->cause_of_death;

        if ($id > 0) {
            $death = PoultryChickDeath::find($id);
            $death->date_of_death = $date_of_death;
            $death->total_deaths = $total_deaths;
            $death->cause_of_death = $cause_of_death;
            $death->save();
        } else {
            $death = new PoultryChickDeath();
            $death->batch_id = $batch_id;
            $death->date_of_death = $date_of_death;
            $death->total_deaths = $total_deaths;
            $death->cause_of_death = $cause_of_death;
            $death->save();
        }


        return redirect()->back()->with('success', 'Poultry death record saved successfully.');
    }


    public function delete($id)
    {
        $death = PoultryChickDeath::find($id);
        $death->delete();

        return redirect()->back()->with('success', 'Poultry death record deleted successfully.');
    }
}
