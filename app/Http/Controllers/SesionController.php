<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;


class SesionController extends Controller
{
    public function index()
    {
        $fontFiles = File::files(public_path('fonts'));
        $fontNames = array_map(function ($file) {
            return pathinfo($file->getFilename(), PATHINFO_FILENAME);
        }, $fontFiles);

        $data = session('sessions');
        $expirationSessions = session('expiredSessions');

        session()->keep(['sessions', 'expiredSessions']);

        if (empty($data)) {
            $data = [
                1 => [
                    'id' => 1,
                    'content' => [
                        "color" => "#000000",
                        "colorTexto" => "#ffffff",
                        "letra" => "Gidugu-Regular",
                        "tamanyo" => "100"
                    ],
                ],
            ];
            Session::put('sessions', $data);
        }
        if (empty($expirationSessions)) {
            $expirationSessions = [];
        }

        Log::info('Data retrieved from session: ' . json_encode($data));

        $currentTime = Carbon::now('Europe/Madrid');
        foreach ($data as $key => $session) {
            if (isset($expirationSessions[$key - 1]) && $expirationSessions[$key - 1] != "Expired" && $currentTime->greaterThanOrEqualTo($expirationSessions[$key - 1])) {
                $data[$key]['content'] = [
                    "color" => "#000000",
                    "colorTexto" => "#ffffff",
                    "letra" => "Gidugu-Regular",
                    "tamanyo" => "100"
                ];
            }
        }

        Log::info('Data expiration from session INDEX: ' . json_encode($expirationSessions));

        return view('index', compact('fontNames', 'data', 'expirationSessions'));
    }
    public function store(Request $request, $id)
    {
        $data = $request->all();
        Log::info('Data received: ' . json_encode($data)); 

        // Recuperar el array de sesiones y fechas de expiración de la sesión
        $sessions = session('sessions', []);
        $expiredSessions = session('expiredSessions', []);
        $expirationTime = Carbon::now('Europe/Madrid')->addSeconds(150);

        // Actualizar los datos de la sesión y la fecha de expiración
        for ($i = 0; $i < count($data); $i++) {
            $sessions[$data[$i]['id']] = $data[$i];
            if (($i+1) == $id) {
                $expiredSessions[$i] = $expirationTime;
            } else {
                if (!isset($expiredSessions[$i]) || $expiredSessions[$i] == "Expired") {
                    $expiredSessions[$i] = "Expired";
                }
            }
        }

        // Guardar los arrays actualizados en la sesión
        Session::put('sessions', $sessions);
        Session::put('expiredSessions', $expiredSessions);
        Log::info('Data expiration from session: ' . json_encode($expiredSessions));

        // Registrar el contenido de las sesiones y las fechas de expiración en el log
        Log::info('Sessions: ' . json_encode($sessions));
        Log::info('AAAAAAAAAAAAAAAAAAAA: ' . json_encode($data[0]['content']));

        // $expirationTime = Carbon::now('Europe/Madrid')->addSeconds(15);
        // Session::put('data_expiration', [$data['id'] => $expirationTime]);



        return redirect()->route('show', ['id' => $id])->with('sessions', $sessions)->with('expiredSessions', $expiredSessions);
    }
    public function show($id)
    {
        $sessions = session('sessions');
        $expirationSessions = session('expiredSessions');

        Log::info('Data retrieved from session: ' . json_encode($sessions));

        session()->keep(['sessions', 'expiredSessions']);

        Log::info('Data expiration from session2: ' . json_encode($expirationSessions));

        $data = $sessions[$id]['content'];
        return view('show', compact('id', 'data'));
    }
    public function delete($id)
    {
        $sessions = session('sessions');
        $expiredSessions = session('expiredSessions');
        Log::info('Data retrieved from session 4: ' . json_encode($expiredSessions));
        unset($sessions[$id]);
        if (!empty($expiredSessions)) {
            unset($expiredSessions[$id]);
        }
        Log::info('Data retrieved from session 5: ' . json_encode($expiredSessions));
        session()->put('sessions', $sessions);
        session()->put('expiredSessions', $expiredSessions);
        return redirect('/') -> with('sessions', $sessions) -> with('expiredSessions', $expiredSessions);
    }
    public function add(Request $request, $id)
    {
        $fontFiles = File::files(public_path('fonts'));
        $fontNames = array_map(function ($file) {
            return pathinfo($file->getFilename(), PATHINFO_FILENAME);
        }, $fontFiles);

        $dataSet = $request->all();

        $sessions = session('sessions',[]);
        $expirationSessions = session('expiredSessions', []);

        
        Log::info('Sessions bef adding: ' . json_encode($sessions));
        //$nextId = count($sessions) > 0 ? max(array_column($sessions, 'id')) + 1 : 1;

        $sessions[$id] = $dataSet;
        $expirationSessions[] = "Expired";


        // Agregar el nuevo elemento al array de sesiones
        Session::put('sessions', $sessions);
        Session::put('expiredSessions', $expirationSessions);

        // Registrar el contenido de las sesiones en el log
        Log::info('Sessions after adding: ' . json_encode($sessions));

        return redirect('/') -> with('sessions', $sessions) -> with('expiredSessions', $expirationSessions);
    }
}

