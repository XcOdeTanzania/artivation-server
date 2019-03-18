<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller
{
    public function basic_email() {
        $data = array('name'=>"Virat Gandhi");
     
        Mail::send(['text'=>'mail'], $data, function($message) {
           $message->to('kalrobbynson@gmail.com', 'Tutorials Point')->subject
              ('Laravel Basic Testing Mail');
           $message->from('kalrobbynson@gmail.com','Virat Gandhi');
        });
        
        return response()->json(['Basic Email Sent. Check your inbox.'], 201);
     }
     public function html_email() {
        $data = array('name'=>"Virat Gandhi");
        Mail::send('mail', $data, function($message) {
           $message->to('kalrobbynson@gmail.com', 'Tutorials Point')->subject
              ('Laravel HTML Testing Mail');
           $message->from('kalrobbynson@gmail.com','Virat Gandhi');
        });
        
        return response()->json(['HTML Email Sent. Check your inbox.'], 201);
     }
     public function attachment_email() {
        $data = array('name'=>"Virat Gandhi");
        Mail::send('mail', $data, function($message) {
           $message->to('kalrobbynson@gmail.com', 'Tutorials Point')->subject
              ('Laravel Testing Mail with Attachment');
           $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
           $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
           $message->from('xyz@gmail.com','Virat Gandhi');
        });
        
        return response()->json(['Email Sent with attachment. Check your inbox.'], 201);
     }
}
