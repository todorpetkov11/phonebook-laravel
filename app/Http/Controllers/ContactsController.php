<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Auth;

class ContactsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $contacts = Contact::where('user_id', '=', Auth::user()->id)->get();
        return response()->json(
            // "success" => true,
            // "message" => "Contacts List",
            // "data" => 
            $contacts
        );
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $imagePath = null;

        if ($request->file('image')) {
            Validator::validate($input, [
                'image' => [
                    'required',
                    File::image()
                        ->max(5120)
                ],
            ]);
            $imagePath = $request->file('image')->store('contact_images');
        };

        $validator = Validator::make($input, [
            'name' => 'required',
            'number' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input['image'] = $imagePath;
        $input['user_id'] = Auth::user()->id;
        $contact = Contact::create($input);

        return response()->json([
            "success" => true,
            "message" => "Contact created successfully.",
            "data" => $contact
        ]);
    }

    public function show(Contact $contact)
    {
        $contact = Contact::find($contact->id);

        if (is_null($contact)) {
            return $this->sendError('Contact not found.');
        }

        return response()->json(
            // "success" => true,
            // "message" => "Contact retrieved successfully.",
            // "data" => 
            $contact
        );
    }

    // FILE UPLOAD DOESN'T WORK WITH PUT/PATCH

    public function update(Request $request, Contact $contact)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'number' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Invalid data",
                "data" => $request->all()
            ]);
        };

        $contact->name = $input['name'];
        $contact->number = $input['number'];
        $contact->save();

        return response()->json([
            "success" => true,
            "message" => "Contact updated successfully.",
            "data" => $contact
        ]);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response()->json([
            "success" => true,
            "message" => "Contact deleted successfully.",
            "data" => $contact
        ]);
    }
}
