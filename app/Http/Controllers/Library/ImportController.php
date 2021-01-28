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
    
    public function concepts() {
        $fname = 'concepts'; 
        $filename = 'import/'.$fname.'.txt';
        $file_content = Storage::disk('local')->get($filename);
        $file_lines = preg_split ("/\r?\n/",$file_content);
//dd($file_lines);
        Import::concepts($file_lines);
print "Понятия сохранены.";        
    }
    
    public function conceptСategories() {
        $fname = 'concept_categories'; 
        $filename = 'import/'.$fname.'.txt';
        $file_content = Storage::disk('local')->get($filename);
        $file_lines = preg_split ("/\r?\n/",$file_content);
//dd($file_lines);
        Import::conceptСategories($file_lines);
print "Темы понятий сохранены.";        
    }
    
    public function conceptPlace() {
        $dname = 'import/concept_place'; 
        $files = Storage::disk('local')->files($dname);
        foreach ($files as $filename) {
            $file_content = Storage::disk('local')->get($filename);
            if (!preg_match("/p(\d+)\.txt$/",$filename, $regs)) {
                dd("Place ID is not matched.");
            }
            $file_lines = preg_split ("/\r?\n/",$file_content);
//    dd($file_lines);
            Import::conceptPlace($regs[1], $file_lines);
        }
print "Словники сохранены.";    
    }
}
