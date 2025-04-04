<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Portfolio;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\validation\validatesRequests;
class SectionController extends Controller
{
    use AuthorizesRequests,DispatchesJobs,validatesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index($portfolio_id)
    {
        $section = Section::where('portfolio_id', $portfolio_id)->get();
        if($section->isEmpty()) {
            return response()->json(["message" => "section is empty"], 404);
        }


        return response()->json(["Sectiont"=>$section],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$portfolio_id)
       {

        $portfolio=Portfolio::find($portfolio_id);
       try{$this->authorize('create',$portfolio);
        if (!$portfolio) {
            return response()->json(["message" => "Portfolio not found"], 404);
        }

      else{
        $request->validate([
            'content' => 'required|string',//tages
            'section_type' => 'required|string',
                    ]);

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        $content = $purifier->purify($request->input('content'));
        $section_type = $purifier->purify($request->input('section_type'));
         $section = Section::create([
            'content' => $content ,
            'portfolio_id' => $portfolio_id,

            'section_type'=> $section_type, ]);

            return response()->json(["message"=>"Sectiont  creating successfully","section"=>$section],200);;
        }
    } catch ( \Illuminate\Auth\Access\AuthorizationException  $e ) {

        return response()->json(['message' => 'not authourize'], 403);
    }


    }

    /**
     * Display the specified resource.
     */
    public function show( $portfolio_id,$section_id)
    {
        $section = Section::where('portfolio_id', $portfolio_id)->where('id', $section_id)->first();
        if(!$section){
            return response()->json(["message"=>"section not found"], 404);
        }
        return response()->json(["Sectiont"=>$section],200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $portfolio_id,$section_id)
    {
        $section = Section::where('portfolio_id', $portfolio_id)->where('id', $section_id)->first();
        if(!$section){
            return response()->json(["message"=>"section not found"], 404);
        }
        try{ $this->authorize('update',$section);
        $request->validate([
            'content' => 'required|string',//tags
            'section_type' => 'required|string',
        ]);
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        $content = $purifier->purify($request->input('content'));
        $section_type = $purifier->purify($request->input('section_type'));
        if(!$section){
         return response()->json(["message"=>"section not found"], 404);}
      {
         $section->content =  $content;
        $section->section_type = $section_type;
        $section->save();
        return response()->json(["message"=>"section  updated successfully","section"=>$section],200);
        }
    } catch ( \Illuminate\Auth\Access\AuthorizationException  $e ) {

        return response()->json(['message' => 'not authourize'], 403);
    }
      }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($portfolio_id,$section_id)
    {
        $section = Section::where('portfolio_id', $portfolio_id)->where('id', $section_id)->first();
      try{ $this->authorize('delete',$section);
        if(!$section ){
            return response()->json(["message"=>"section  not found"], 404);}
        {

            $section->delete();
        return response()->json(["message"=>"section t deleted successfully"],200);
    }
} catch ( \Illuminate\Auth\Access\AuthorizationException  $e ) {

    return response()->json(['message' => 'not authourize'], 403);
}

        }
}
