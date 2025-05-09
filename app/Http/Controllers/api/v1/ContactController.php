<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->contacts();

        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'deleted':
                    $query->onlyTrashed();
                    break;
                case 'active':
                    $query->whereNull('deleted_at');
                    break;
                case 'all':
                    $query->withTrashed();
                    break;
                default:
                    $query->whereNull('deleted_at');
                    break;
            }
        } else {
            $query->withTrashed();
        }

        return response()->json($query->get());
    }
    public function showContactsPage()
    {
        return view('contacts');
    }

    public function store(Request $request)
    {
        $contact = $request->user()->contacts()->create($request->all());
        return response()->json($contact, 201);
    }

    public function update(Request $request, $id)
    {
        $contact = $request->user()->contacts()->findOrFail($id);
        $contact->update($request->all());
        return response()->json($contact);
    }

    public function destroy(Request $request, $id)
    {
        $contact = $request->user()->contacts()->findOrFail($id);
        $contact->delete();
        return response()->json(null, 204);
    }
}
