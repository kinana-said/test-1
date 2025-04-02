<?php

namespace App\Http\Controllers;
use App\Models\Work;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($portfolio_id)
    {
        $work = Work::with('user')->where('portfolio_id', $portfolio_id)->get();
        return response()->json( $work);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$portfolio_id)
       {

        $work = Work::find($portfolio_id);
        if(!$work){
            return response()->json(["message"=>"Portfolio not found you  don't can creating Work"], 404);}
      else{
        $request->validate([
            'title' => 'required|string',
            'link' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    ]);
                $work = new Work;
                $work->content = $request->content;
                $work->portfolio_id= $request-> $portfolio_id;
                $work->user_id=auth()->id();
                $work->section_type= $request->section_type;
                 if ($request->hasFile('image')) {
                    $work->image = $request->file('image')->store('image/works_images', 'public');
                }
                $work->save();
            return response()->json(["message"=>"Work  creating successfully","work"=>$work],200);;
        }


    }

    /**
     * Display the specified resource.
     */
    public function show($sections_id)
    {
        $work = Work::where("id",$sections_id)->first();
        if(!$work){
            return response()->json(["message"=>"work not found"], 404);
        }
        $data=$work->map(function($work)
        {return[

        "title"=>$work->title,
        "content"=>$work->content,
        "link"=>$work->link,

        ];
        });
        if ($work->hasFile('image')) {
            return response()->json(["Sectiont"=>$data,"image"=>$work->image_url,],200);
        }

        return response()->json(["Sectiont"=>$data],200);
        }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $sections_id,$portfolio_id)
    {
        $work = Work::where('portfolio_id',$portfolio_id)->findOrFail($sections_id);

        if(!$work){
            return response()->json(["message"=>"work not found"], 404);}
        $request->validate([
            'content' => 'sometimes|required|string',
            'title' => 'required|string|max:255',
            'link' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);

      {  $work->content = $request->content;
        $work->title= $request->title;
        $work->link= $request->link;
        if ($request->hasFile('image')) {
            $work->image = $request->file('image')->store('image/works_images', 'public');
        }
        $work->save();
        return response()->json(["message"=>"work  updated successfully","work"=>$work],200);
        }
      }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($work_id,$portfolio_id)
    {
        $work = Work::where('portfolio_id',$portfolio_id)->findOrFail($work_id);
        if(!$work ){
            return response()->json(["message"=>"work isnot found"], 404);}
        {
            Storage::delete( $work->image);
            $work->delete();
        return response()->json(["message"=>"work  deleted successfully"],200);
    }

        }
}

