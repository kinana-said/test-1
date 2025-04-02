<?php
namespace App\Http\Controllers;
use App\Models\Contact;
use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
namespace App\Http\Controllers;
use App\Models\Portfolio;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $portfolios=Portfolio::with(["user","works","sections"])->get();
        if (!$portfolios) {
            return response()->json(["message" => "Portfolio is empty"], 404);
        }
        return response()->json($portfolios ,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd(auth()->user());

        $request->validate([
            'title' => 'required|string|max:16',
            'description' => 'required|string|max:255',


        ]);
        $portfolio = new Portfolio();
        $portfolio->user_id = auth()->id();
        $portfolio->title = $request->title;
        $portfolio->description = $request->description;
        $portfolio->save();

        return response()->json($portfolio, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $portfolio=Portfolio::find($id);


    if (!$portfolio) {
        return response()->json(["message" => "Portfolio not found"], 404);
    }
    $data = [
        "id" => $portfolio->id,
        "name_user" => $portfolio->user->name,
        "image" => $portfolio->image,
        "title" => $portfolio->title,
        "description" => $portfolio->content,

        "Sections" => $portfolio->sections->map(function ($section) {
            return [

                "content" => $section->content,
                "section_type" => $section->section_type,
                ];
        }),
        "Works" => $portfolio->works->map(function ($works) {
            return [
                "title" => $works->title,
                "content" => $works->content,
                "link" => $works->link,
                "image" => $works->image_url,

                ];
        }),
        "Sections" => $portfolio->sections->map(function ($section) {
            return [
                "content" => $section->content,
                "section_type" => $section->section_type,
                ];
        }),];


        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $portfolio=Portfolio::with(["user","works","sections"])->find($id);


        if (!$portfolio) {
            return response()->json(["message" => "Portfolio not found"], 404);
        }
        $request->validate([
            'title' => 'required|string|max:16',
            'description' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',

        ]);
        $portfolio->user_id = auth()->id();
        $portfolio->title = $request->title;
        $portfolio->description = $request->description;
        $portfolio->save();

        return response()->json(["message"=>"Portfolio  updated successfully","portfolio"=>$portfolio],200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $portfolio=Portfolio::find($id);
        if(!$portfolio){
            return response()->json(["message"=>"portfolio not found"], 404);}
        {

            $portfolio->delete();
        return response()->json(["message"=>"$portfolio  deleted successfully"],200);
    }
    }
}
