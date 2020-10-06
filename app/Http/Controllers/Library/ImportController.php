<?php

namespace App\Http\Controllers\Library;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;

use App\Library\Import;

class ImportController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth:admin,/');
    }
    
    public function placeCoord(Request $request) {
        $fname = 'place_coord'; 
        $filename = 'import/'.$fname.'.txt';
        $file_content = Storage::disk('local')->get($filename);
        $file_lines = preg_split ("/\r?\n/",$file_content);
//dd($file_lines);
        Import::placeCoord($file_lines);
print "Координаты сохранены.";        
    }
    
    public function questions(Request $request) {
        $fname = 'questions'; 
        $filename = 'import/'.$fname.'.txt';
        $file_content = Storage::disk('local')->get($filename);
        $file_lines = preg_split ("/\r?\n/",$file_content);
//dd($file_lines);
        Import::questions($file_lines);
print "Вопросы сохранены.";        
    }
    
    public function qsections() {
        Import::writeSections();
print "Разделы вопросов сохранены.";        
    }
}
