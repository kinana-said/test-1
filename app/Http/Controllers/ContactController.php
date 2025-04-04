<?php

namespace App\Http\Controllers;
use App\Models\Portfolio;
use App\Models\Contact;
use Illuminate\Http\Request;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\validation\validatesRequests;
class ContactController extends Controller
{
    use AuthorizesRequests,DispatchesJobs,validatesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index($portfolio_id)
      {
        $contact = Contact::where('portfolio_id', $portfolio_id)->get();
        if($contact->isEmpty()) {
            return response()->json(["message" => "contact is empty"], 404);
        }
        return response()->json(["Contact"=> $contact],200);
       }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$portfolio_id)
       {
        $portfolio=Portfolio::find($portfolio_id);
      try{  $this->authorize('create',$portfolio);
        if (!$portfolio) {
            return response()->json(["message" => "Portfolio not found you  don't can creating Contact"], 404);
        }

      else{
        $request->validate([
            'email'=> 'required|string|max:27',
            'phone'=> 'required|string|max:20',
            'githup'=> 'required|string|max:20',
            'linkedlin'=> 'required|string|max:20',
                    ]);

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        $email = $purifier->purify($request->input('email'));
        $phone = $purifier->purify($request->input('phone'));
        $githup = $purifier->purify($request->input('githup'));
        $linkedlin = $purifier->purify($request->input('linkedlin'));

   $contact = Contact::create([
            'email' => $email,
            'portfolio_id' => $portfolio_id,
            'phone'=> $phone,
            'githup'=> $githup,
            'linkedlin'=>$linkedlin,  ]);

            return response()->json(["message"=>"Contact  creating successfully","contact"=>$contact],200);;
        } } catch ( \Illuminate\Auth\Access\AuthorizationException  $e ) {

            return response()->json(['message' => 'not authourize'], 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($portfolio_id,$contact_id)
    {
        $contact = Contact::where('portfolio_id', $portfolio_id)->where('id', $contact_id)->first();

    if (!$contact) {

        return response()->json(["message" => "Contact not found."], 404);
    }
        return response()->json(["Contact"=>$contact],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $portfolio_id,$contact_id)
    {
        $contact = Contact::where('portfolio_id', $portfolio_id)->where('id', $contact_id)->first();
       try{ $this->authorize('update',$contact);
        if(!$contact ){
            return response()->json(["message"=>"contact   not found"], 404);
         }

        $request->validate([
            'email'=>'required|string',
            'phone'=>'required|string',
            'githup'=>'required|string',
            'linkedlin'=>'required|string',
        ]);
      {
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        $email = $purifier->purify($request->input('email'));
        $phone = $purifier->purify($request->input('phone'));
        $githup = $purifier->purify($request->input('githup'));
        $linkedlin = $purifier->purify($request->input('linkedlin'));

        $contact->email = $email;
        $contact->portfolio_id = $portfolio_id;
        $contact->phone=$phone;
        $contact->githup = $githup;
        $contact->linkedlin=$linkedlin;

        $contact->save();
        return response()->json(["message"=>"contact  updated successfully","contact"=>$contact],200);
        } } catch ( \Illuminate\Auth\Access\AuthorizationException  $e ) {

            return response()->json(['message' => 'not authourize'], 403);
        }
      }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($portfolio_id,$contact_id)
    {
        $contact = Contact::where('portfolio_id', $portfolio_id)->where('id', $contact_id)->first();
       try{ $this->authorize('delete',$contact);
        if(!$contact ){
            return response()->json(["message"=>"contact   not found"], 404);}
        {

            $contact ->delete();
        return response()->json(["message"=>"contact  deleted successfully"],200);
    } } catch ( \Illuminate\Auth\Access\AuthorizationException  $e ) {

        return response()->json(['message' => 'not authourize'], 403);
    }

        }
}
