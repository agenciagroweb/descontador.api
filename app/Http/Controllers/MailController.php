<?php

namespace App\Http\Controllers;

use Mail;
use JWTAuth;
use App\Http\Requests;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'contact', 'press']]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function contact(Request $request)
    {
        $validate = [
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required'
        ];

        $validator = Controller::validator($request, $validate);

        if ($validator !== true)
            return Controller::response(Controller::error(38), 400);

        $input = $request->all();

        $data = array(
            'email' => $input['email'],
            'name' => $input['name'],
            'subject' => $input['subject'],
            'message' => $input['message'],
            'to' => 'eu@leonardomoreira.com.br'
        );


        Mail::send('emails.contact', ['input' => $data], function ($m) use ($data) {

            $m->to($data['to'], 'Descontador');

            $m->replyTo($data['email'], $data['name']);
            $m->subject('Contato - Descontador');
        });

        return Controller::response($data, 200);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function press(Request $request)
    {
        $validate = [
            'name' => 'required',
            'email' => 'required',
            'message' => 'required'
        ];

        $validator = Controller::validator($request, $validate);

        if ($validator !== true)
            return Controller::response(Controller::error(38), 400);

        $input = $request->all();

        $data = array(
            'email' => $input['email'],
            'name' => $input['name'],
            'message' => $input['message'],
            'to' => 'weare@groweb.com.br'
        );

        Mail::send('emails.press', ['input' => $data], function ($m) use ($data) {
            $m->to($data['to'], 'We Are Esports')
                ->replyTo($data['email'], $data['name'])
                ->subject('Imprensa - We Are Esports');
        });

        return Controller::response($data, 200);
    }

}
