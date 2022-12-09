<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/myroombanner/{filename}', function ($filename) {
    $path = storage_path('roombanner/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
})->name('show.roombanner');

Route::get('/prereg/{filename}', function ($filename) {
    $path = storage_path('prereg/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
})->name('show.prereg.image');

Route::get('/chat_images/{filename}', function ($filename) {
    $path = storage_path('chat_images/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
})->name('show.chatImages');

Route::get('/chat_audio/{filename}', function ($filename) {
    $path = storage_path('chat_audio/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
})->name('show.chatAudio');

Route::get('/chat_files/{filename}', function ($filename) {
    $path = storage_path('chat_files/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
})->name('show.chatFiles');

Route::get('/recording/{filename}/{type}', function ($filename, $type) {

    $path = "https://meet3.konn3ct.com/presentation/$filename/video/webcams.mp4";

    if ($type == "video") {
        $filenam = "Recording-video-$filename.mp4";
    } elseif ($type == "screenshare") {
        $filenam = "Recording-screenshare-$filename.mp4";
        $path = "https://meet3.konn3ct.com/presentation/$filename/deskshare/deskshare.mp4";
    } elseif ($type == "chats") {
        $filenam = "Recording-chats-$filename.txt";
        $path = "https://meet3.konn3ct.com/presentation/$filename/aslides_new.xml";
    } else {
        return redirect()->route('recording')->with('error', 'Type does not exist');
    }


    try {
        $mime = 'application/force-download';

        header('Content-Type: ' . $mime);

        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . $filenam);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        ob_clean();
        flush();
        readfile($path);
    } catch (Exception $e) {
        echo "File not found";
    }

})->name('download.recording');
