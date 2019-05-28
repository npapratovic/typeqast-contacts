<?php

namespace App\Http\Controllers;

use Auth;
use App\Contact; 
use Illuminate\Support\Facades\Input;
use App\ContactNumbers;
use App\Favorite;
use Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\FileUploadTrait;
use DB;

class ContactController extends Controller
{

    //I am using FileUploadTrait to handle file uploads
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $contacts = Contact::latest()->get();
 

        return view('contacts.index',compact('contacts'));

    }
  
    public function search()
    {
 
        $q = Input::get ('q');
    
        $contacts = Contact::where ('first_name', 'LIKE', '%'.$q.'%' )
                            ->orWhere ('last_name', 'LIKE', '%'.$q.'%')
                            ->get ();
        
        if (count($contacts)>0) {
  
            return view('contacts.index')->with('contacts', $contacts)->with('success','Your search results.');

        }

        else {

            $contacts = Contact::latest()->get();

            return view('contacts.index')
                ->with('contacts', $contacts)
                ->with('error', 'No results found, try different query! Below is list of all contacts.');
        }
 
 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

         $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
        ]);

        // check if items array is empty,
        // if empty let user know that something went wrong
        if(empty($request->number))
        {
           // send user back to create back.
            return redirect()->back()->with('message', 'Please add at least one contact number!');
        }

        // else we will add the contact to DB as usual.

        // look in app/Http/Traits/FileUploadTrait for definition of saveFiles method
        $request = $this->saveFiles($request);
 
        Contact::create($request->all());

        $last_inserted_contact = DB::table('contacts')->orderBy('created_at', 'desc')->first();

        $last_inserted_contact = $last_inserted_contact->id;
 
        // initialize an empty array to store numbers for mass insertion.
        $contacts_number = [];

        // Loop through numbers array for each item.
        foreach ($request->number as $item)
        {
   
            // build the invoice numbers array.
            $contacts_number[] = [
                'number' => $item['number_name'],
                'cell' => $item['cell'],
                'contact_id' => $last_inserted_contact

            ];

        }

        // mass insert the contact_number to reduce database costs.
        DB::table('contact_numbers')->insert($contacts_number);

        return redirect()->route('contacts.index')->with('success','Contact created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        
        // if not proven other way, contact is not favorite
        $check_fav = '0';

        if (Auth::user()) {   // user is logged in, check if this contact is in favorite list

            //check if contact is favorite
            $contact_id = $contact->id;  

            $check_if_emty = Favorite::where('contact_id', $contact_id)->first();   

            if ($check_if_emty) {

                //contact is favorite
                $check_fav = '1';
            
            } else {
                
                $check_fav = '0';
           
            }

        }  
  
        return view('contacts.show',compact('contact', 'check_fav'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        $contact = Contact::where('id', $contact->id)->firstOrFail();

        $contact_number = ContactNumbers::where('contact_id', $contact->id)->get();
 
        return view('contacts.edit',compact('contact', 'contact_number'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

       // I have separated updating contact info and contact numbers in two methods (ContactController@update & ContactController@updatePhoneNumbers)
        // look in app/Http/Traits/FileUploadTrait for definition of saveFiles method
        $request = $this->saveFiles($request);
 
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
        ]);
    
        $contact = Contact::findOrFail($request->contact_id);
  
        $contact->update($request->all());
 
        return redirect()->back()->with('success','Contact data updated successfully.');

    }


    /**
     * Update phone numbers in storage. (phone numbers belonging to specific contact)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function updatePhoneNumbers(Request $request)
    {
    
        $contact_id = $request->contact_id;

        $phone_numbers = $request->number;
 
       // if user has request to delete the item, delete it.
        if ($request->has('delete_item'))
        {
              
            $phone_numbers_to_delete = $request->delete_item;

            // Loop through numbers array for each item.
            foreach ($phone_numbers_to_delete as $key => $value)
            {
                $contact_number_id = $value;
                 
                DB::table('contact_numbers')->where('id', '=', $contact_number_id)->delete();
 
            }
 
            return redirect()->back()->with('success','Contact number deleted successfully.');
 
        } 
 
        // Loop through numbers array for each item.
        foreach ($phone_numbers as $item)
        {
            $number = $item['number_name'];
            $cell = $item['cell'];
            $id = $item['contact_number_id'];
 
            DB::table('contact_numbers')->updateOrInsert(
                ['id' => $id], 
                ['number' => $number, 'cell' => $cell, 'contact_id' => $contact_id]
            );
  
        }
 
        return redirect()->back()->with('success','Contact numbers updated successfully.');
 
    }

    /** 
    * Add specific contact to my favorites list
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
    */
    public function addFavorite (Request $request, Contact $contact) 
    {

        $contact_id = $request->contact_id;

        $user_id = $request->user_id;

        DB::table('favorites')->insert(
            ['contact_id' => $contact_id, 'user_id' => $user_id]
        );

        return redirect()->back()->with('success','Number added to favorites');

    } 

    /** 
    * Remove specific contact to my favorites list
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
    */
    public function removeFavorite (Request $request, Contact $contact) 
    {
    
        $contact_id = $request->contact_id;

        $user_id = Auth::user()->id;

        $query = DB::table('favorites')->where('contact_id', '=', $contact_id)->where('user_id', '=', $user_id)->delete();

        return redirect()->back()->with('success','Number removed from favorites');

    } 

    /**
     * Display a listing of my favorites.
     *
     * @return \Illuminate\Http\Response
     */
    public function myFavorites()
    {
 
        $id = Auth::user()->id;

        //I have converted this to array because I need additional data (name, image, etc.)
        //Maybe db_table join would be better? 
          
        $favorites = Favorite::where('user_id', $id)  
               ->get()->toArray();  

        // initialize an empty array favorite_contacts
        $favorite_contacts = [];

        if ($favorites) {

            // Loop through favorite_contacts array to get additional data (name, image, etc.)
            foreach ($favorites as $fav)
            {
                $contact_id = $fav['contact_id'];
                $contact = Contact::where('id', $contact_id)
                    ->get()->toArray();
     
                // build the favorite contacts array.
                $favorite_contacts[] = [
                    'first_name' => $contact['0']['first_name'],
                    'last_name' => $contact['0']['last_name'],
                    'contact_id' => $contact['0']['id'],
                    'image' => $contact['0']['image']

                ];

            }
        }  
  
        return view('contacts.favorites',compact('favorite_contacts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
    */
    public function destroy(Contact $contact)
    {
        
        $contact_id = $contact->id;
        
        $query = DB::table('favorites')->where('contact_id', '=', $contact_id)->delete();
        
        $contact->delete();

        return redirect()->route('contacts.index')->with('success','Contact deleted successfully.');
    }
}
