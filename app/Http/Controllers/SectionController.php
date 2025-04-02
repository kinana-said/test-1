<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Models\User;
use HTMLPurifier;
use HTMLPurifier_Config;
class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($portfolio_id)
    {
        $section = Section::with('user')->where('portfolio_id', $portfolio_id)->get();
        return response()->json($section);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$portfolio_id)
       {

        $section = Section::find($portfolio_id);
        if(!$section){
            return response()->json(["message"=>"Portfolio not found you  don't can creating Sectiont"], 404);}
      else{
        $request->validate([
            'content' => 'required|string',
            'section_type' => 'required|string',

                    ]);

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        $content = $purifier->purify($request->input('content'));

   $section = Section::create([
            'content' => $content ,
            'portfolio_id' => $portfolio_id,
            'user_id' => auth()->id(),
            'section_type'=> $request->section_type, ]);

            return response()->json(["message"=>"Sectiont  creating successfully","section"=>$section],200);;
        }


    }

    /**
     * Display the specified resource.
     */
    public function show($section_id)
    {
        $section = Section::where("id",$section_id)->first();
        if(!$section){
            return response()->json(["message"=>"section not found"], 404);
        }
        return response()->json(["Sectiont"=>$section],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $section_id,$portfolio_id)
    {
        $section = Section::where('portfolio_id',$portfolio_id)->findOrFail($section_id);

        $request->validate([
            'content' => 'required|string',//tags
            'section_type' => 'required|string',
        ]);
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        $content = $purifier->purify($request->input('content'));
        if(!$section){
         return response()->json(["message"=>"section not found"], 404);}
      {  $section->content =  $content;
        $section->section_type = $request->section_type;
        $section->save();
        return response()->json(["message"=>"section  updated successfully","section"=>$section],200);
        }
      }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($section_id,$portfolio_id)
    {
        $section = Section::where('portfolio_id',$portfolio_id)->findOrFail($section_id);
        if(!$section ){
            return response()->json(["message"=>"section  not found"], 404);}
        {

            $section->delete();
        return response()->json(["message"=>"section t deleted successfully"],200);
    }

        }
}
