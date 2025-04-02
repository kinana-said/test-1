<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($portfolio_id)
    {
        $contact = Contact::with('user')->where('portfolio_id', $portfolio_id)->get();
        return response()->json($contact);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$portfolio_id)
       {

        $contact = Contact::find($portfolio_id);
        if(!$contact){
            return response()->json(["message"=>"Portfolio not found you  don't can creating Contact"], 404);}
      else{
        $request->validate([
            'email'=> 'required|string',
            'phone'=> 'required|string',
            'githup'=> 'required|string',
            'linkedlin'=> 'required|string',
                    ]);

   $contact = Contact::create([
            'email' => $request->email,
            'portfolio_id' => $portfolio_id,
            'user_id' => auth()->id(),
            'phone'=> $request->phone,
            'githup'=> $request->githup,
            'linkedlin'=> $request->linkedlin,  ]);

            return response()->json(["message"=>"Contact  creating successfully","contact"=>$contact],200);;
        }


    }

    /**
     * Display the specified resource.
     */
    public function show($sections_id)
    {
        $contact = Contact::where("id",$sections_id)->first();
        if(!$contact){
            return response()->json(["message"=>"section not found"], 404);
        }
        return response()->json(["Contact"=>$contact],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $contact_id,$portfolio_id)
    {
        $contact = Contact::where('portfolio_id',$portfolio_id)->findOrFail($contact_id);

        $request->validate([
            'email'=>'required|string',
            'phone'=>'required|string',
            'githup'=>'required|string',
            'linkedlin'=>'required|string',
        ]);

        if(!$contact){
         return response()->json(["message"=>"contact not found"], 404);}
      { $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->githup = $request->githup;
        $contact->linkedlin = $request->linkedlin;
        $contact->save();
        return response()->json(["message"=>"contact  updated successfully","contact"=>$contact],200);
        }
      }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($contact_id,$portfolio_id)
    {
        $contact = Contact::where('portfolio_id',$portfolio_id)->findOrFail($contact_id);
        if(!$contact  ){
            return response()->json(["message"=>"contact   not found"], 404);}
        {

            $contact ->delete();
        return response()->json(["message"=>"contact  deleted successfully"],200);
    }

        }
}
