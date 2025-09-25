<?php

namespace App\Http\Controllers;

use App\Mail\ContactUsMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactUsController extends Controller
{
 
    public function index()
    {
        return view('auth.passwords.email')->with('info', 'Mensaje enviado');
        // return view('emails.contactanos');
    }

    public function create()
    {
        //
    }

   
    public function store(Request $request)
    // public function store(StoreContact $request)
    {
        //      $request->validate([
        //     'name' => 'required',
        //     'correo' => 'required|email',
        //     'mensaje' => 'required',  
        // ]);
        // $request->validate(['correo' => 'required|email','mensaje' => 'required' ]);
        $correo = new ContactUsMailable($request->all()); 
        // dd($request->all());
        Mail::to($request->email)->send($correo);
       
        return 'Mensaje enviado';
        // return redirect()->route('contactanos.index')->with('info', 'Mensaje enviado');

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
