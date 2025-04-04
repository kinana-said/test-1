<?php

namespace App\Http\Controllers;
use App\Models\Work;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\validation\validatesRequests;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Storage;
class WorkController extends Controller
{
    use AuthorizesRequests,DispatchesJobs,validatesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index($portfolio_id)
    {
        $work = Work::where('portfolio_id', $portfolio_id)->get();
        if ($work->isEmpty()) {
            return response()->json(["message" => "work is empty"], 404);
        }

        return response()->json( $work);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$portfolio_id)
       {
        $portfolio=Portfolio::find($portfolio_id);
       try{ $this->authorize('create',$portfolio);
        if (!$portfolio) {
            return response()->json(["message" => "Portfolio not found"], 404);
        }
      else{
        $request->validate([
            'title' => 'required|string',
            'link' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    ]);

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        $content = $purifier->purify($request->input('content'));
        $link = $purifier->purify($request->input('link'));
        $title = $purifier->purify($request->input('title'));

                $work = new Work;
                $work->title = $title;
                $work->content =  $content;
                $work->portfolio_id = $portfolio_id;
                $work->link = $link;

                 if ($request->hasFile('image')) {
                    $work->image = $request->file('image')->store('image/works_images', 'public');
                }
                $work->save();
            return response()->json(["message"=>"Work  creating successfully","work"=>$work],200);;
        } } catch ( \Illuminate\Auth\Access\AuthorizationException  $e ) {

            return response()->json(['message' => 'not authourize'], 403);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show($portfolio_id,$work_id)
    {
        $work = Work::where('portfolio_id', $portfolio_id)->where('id', $work_id)->first();
        if(!$work){
            return response()->json(["message"=>"work not found"], 404);
        }
              $data=["title" => $work->title,
                "content" => $work->content,
                "link" => $work->link,
                "image" => $work->image_url,
                                       ];

        return response()->json([ $data],200);
        }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$work_id,$portfolio_id)
    {
        $work = Work::where('portfolio_id', $portfolio_id)->where('id', $work_id)->first();
       try{ $this->authorize('update',$work);
        if(!$work){
            return response()->json(["message"=>"work not found"], 404);}
        $request->validate([
            'content' => 'required|string',
            'title' => 'required|string|max:255',
            'link' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);

      {
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        $content = $purifier->purify($request->input('content'));
        $link = $purifier->purify($request->input('link'));
        $title = $purifier->purify($request->input('title'));


        $work->content =  $content;
        $work->title= $title;
        $work->link= $link;
        if ($request->hasFile('image')) {
            $work->image = $request->file('image')->store('image/works_images', 'public');
        }
        $work->save();
        return response()->json(["message"=>"work  updated successfully","work"=>$work],200);
        }
    } catch ( \Illuminate\Auth\Access\AuthorizationException  $e ) {

        return response()->json(['message' => 'not authourize'], 403);
    }
      }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($portfolio_id,$work_id)
    {
        $work = Work::where('portfolio_id', $portfolio_id)->where('id', $work_id)->first();
        try{$this->authorize('delete',$work);
        if(!$work ){
            return response()->json(["message"=>"work isnot found"], 404);}
        {
            Storage::delete($work->image);
            $work->delete();
        return response()->json(["message"=>"work  deleted successfully"],200);
    } } catch ( \Illuminate\Auth\Access\AuthorizationException  $e ) {

        return response()->json(['message' => 'not authourize'], 403);
    }

        }
}

