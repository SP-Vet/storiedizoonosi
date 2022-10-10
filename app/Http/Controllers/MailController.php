<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller {
   /*épublic function basic_email() {
      $data = array('name'=>"X Y");
      Mail::send(['text'=>'mail'], $data, function($message) {
         $message->to('xxxxxx@xxxx.com', 'Tutorials Point')->subject
            ('Laravel Basic Testing Mail');
         $message->from('xxx@xxx.com','A B');
      });
      echo "Basic Email Sent. Check your inbox.";
   }*/
   /*public function html_email() {
      $data = array('name'=>"X Y");
      Mail::send('mail', $data, function($message) {
         $message->to('xxxxxx@xxxx.com', 'Tutorials Point')->subject
            ('Laravel HTML Testing Mail');
         $message->from('xxxxxx@xxxx.com','Virat Gandhi');
      });
      echo "HTML Email Sent. Check your inbox.";
   }*/
   /*public function attachment_email() {
      $data = array('name'=>"X Y");
      Mail::send('mail', $data, function($message) {
         $message->to('xxxxxx@xxxx.com', 'Tutorials Point')->subject
            ('Laravel Testing Mail with Attachment');
         $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
         $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
         $message->from('xyz@gmail.com','Virat Gandhi');
      });
      echo "Email Sent with attachment. Check your inbox.";
   }*/
}
?>