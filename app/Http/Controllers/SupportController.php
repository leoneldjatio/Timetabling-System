<?php

namespace App\Http\Controllers;

/**
 * @author Go-Groups LTD
 * Created by PhpStorm.
 * User: ewangclarks
 * Date: 13/01/17
 * Time: 3:17 PM
 *
 */

use App\Http\Requests\MailRequest;
use App\Http\Requests\SMSRequest;
use App\Mail\MailSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Nexmo\Laravel\Facade\Nexmo;

class SupportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Send assignments to Lecturers
     *
     * @param MailRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendMail(MailRequest $request)
    {
        $data = array(
            'to' => $request->get('to'),
            'from' => $request->get('from'),
            'title' => $request->get('title'),
            'subject' => $request->get('subject'),
            'content' => $request->get('content'),
            'attachement' => null,
            'photo' => null
        );
        if ($request->hasFile('photo')) {
            if ($request->file('photo')->isValid()) {
                try {
                    $file = $request->file('photo');
                    $extension = $file->getClientOriginalExtension();
                    if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {
                        $name = time() . '.' . $extension;
                        $request->file('photo')->move(storage_path('mails/photos/'), $name);
                        $data['photo'] = storage_path('mails/photos/' . $name);
                    } else {
                        return redirect()->back()->with('status', 'Wrong image format');
                    }
                } catch (Illuminate\Filesystem\FileNotFoundException $e) {

                }
            }

        }
        //get the attachment file,upload it to the storage/mail path then send it in the email
        if ($request->hasFile('files')) {
            if ($request->file('files')->isValid()) {
                try {
                    $file = $request->file('files');
                    $extension = $file->getClientOriginalExtension();
                    if (($extension != 'mp4') && ($extension != '3gp') && ($extension != 'flv') && ($extension != 'VOB') && ($extension != 'mkv') && ($extension != 'jpg') && ($extension != 'jpeg') && ($extension != 'png') && ($extension != 'mp3')) {
                        $name = time() . '.' . $extension;
                        $request->file('files')->move(storage_path('mails/'), $name);
                        $data['attachment'] = storage_path('mails/' . $name);
                    } else {
                        return redirect()->back()->with('status', 'Wrong file format');
                    }
                } catch (Illuminate\Filesystem\FileNotFoundException $e) {

                }
            }

        }
        //call the facade mailing class to enact email sending
        Mail::to($data['to'])->send(new MailSupport(Auth::user()->user_name, $data));
    }

    /**
     * send sms to Go-Groups  for support
     *
     * @param SMSRequest $request
     */

    public function sendSMS(SMSRequest $request)
    {
        $data = array(
            'from' => $request->get('from'),
            'title' => $request->get('title'),
            'message' => $request->get('message')
        );
        //send sms using the nexmo API
        Nexmo::message()->send([

            'to' => '+237673676301',
            'from' => $data['from'],
            'text' => $data['title'] . ' \n ' . $data['message']
        ]);
    }
}
