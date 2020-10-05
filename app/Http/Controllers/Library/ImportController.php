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
}
