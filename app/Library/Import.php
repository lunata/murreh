<?php

namespace App\Library;

use App\Models\Geo\Place;

use App\Models\Ques\Answer;
use App\Models\Ques\Qsection;
use App\Models\Ques\Question;

/**
 */
class Import {
    public static function placeCoord($lines) {
        foreach ($lines as $line) {
            $line = trim($line);
            if (!$line) {
                continue;
            }
            if (!preg_match("/^(\d+)\.\s+(\d+\.\d+),\s+(\d+\.\d+)$/", $line, $regs)) {
                dd("Неправильный разбор строки ".$line);
            }
            self::writePlaceCoord($regs);
        }        
    } 
    
    public static function writePlaceCoord($data) {
        $place = Place::find($data[1]);
        if (!$place) {
            dd("Населенный пункт с ID=".$data[1]." отсутствует.");
        }
        $place->fill(['latitude'=>$data[2], 'longitude'=>$data[3]])->save();
    }
    
    /**
     * $line: 
       1	20	Как называет себя карел?	karjala – a liygi – b liydi – c

     * 
     * @param array $lines
     */
    public static function questions(array $lines) {
print "<pre>";            
        foreach ($lines as $line) {
            $line = trim($line);
            if (!$line) {
                continue;
            }
            if (!preg_match("/^(\d+)\t(\d+)\t(\d+)\t([^\t]+)\t*([^\t]*)$/", $line, $regs)) {
                dd("Неправильный разбор строки ".$line);
            }
            self::writeQuestion($regs);
            self::writeAnswers($regs[2], trim($regs[5]));
//dd($regs);            
        }        
    } 
    
    public static function writeQuestion(array $data) {
        $section_id = Question::getSectionIDBySubsectionID($data[1]);
        if (!$section_id) {
            dd ("Не определился раздел ".$data[1]." для вопроса ".$data[2]);
        }
        $question = Question::create(['id'=>$data[2], 'section_id'=>$section_id, 'qsection_id'=>$data[1], 'question'=>$data[4], 'sequence_number'=>$data[3]]);
    }
    
    public static function writeAnswers(int $question_id, string $answers) {
        if (!$answers) {
            return null;
        }
        $code = 'a';
        $pos_id = mb_strpos ($answers, "– $code");
        while ($answers && $pos_id) {
            $data = ['question_id'=>$question_id, 'code'=>$code, 'answer'=>trim(mb_substr($answers, 0, $pos_id-1))];
            $answer = Answer::create($data);
print_r ($data);            
            $answers = trim(mb_substr($answers, $pos_id+3));  
            $code++;
            $pos_id = mb_strpos ($answers, "– $code");
        }
/*        
        while ($answers && preg_match("/^([^–]+)\s*–\s*".$code."\s*(.*)$/", $answers, $regs)) {
            $data = ['question_id'=>$question_id, 'code'=>$code, 'answer'=>trim($regs[1])];
//            $answer = Answer::createOrFail(['question_id'=>$question_id, 'code'=>$code, 'answer'=>$regs[1]]);
print_r ($data);            
            $answers = $regs[2];  
            $code++;
        }
 * 
 */
        if ($answers) {
            dd("Остался ответ $question_id: $answers");
        }
    }
    
    public static function writeSections() {
/*        $sections = [
                1 => "Социолингвистическая информация",  
                2 => "Фонетика",  
                3 => "Морфология", 
                4 => "Лексика",
              ];
        foreach ($sections as $section_id => $title) {
            Qsection::create(['id'=>$section_id, 'parent_id'=>0, 'title'=>$title]);
        }
    }
    
    public static function writeSubsections() {*/
        $subsections = [
                1 => [1, "Сведения о названиях"],
                2 => [2, "Дифтонги"],
                3 => [2, "Звонкий / глухой согласный"],
                4 => [2, "y/u в VV"],
                5 => [2, "Сохранение i в дифтонге в первом слоге"],
                6 => [2, "Сохранение i в дифтонге во втором и третьем слоге"],
                7 => [2, "Гласный конца слова"],
                8 => [2, "Дифтонг конца слова"],
                9 => [2, "Гласные i, u, o конца слова"],
                10=> [2, "Гармония"],
                11=> [2, "Лабиализация"],
                12=> [2, "j / d"],
                13=> [2, "ieh / jah / d’ah"],
                14=> [2, "z / zz / dž"],
                15=> [2, "Чередование с j / 0 (1)"],
                16=> [2, "Чередование с j / 0 (2)"],
                17=> [2, "Чередование с j / 0 (3)"],
                18=> [2, "ae / ai / oa; äe / äi / eä"],
                19=> [2, "ie / ia"],
                20=> [2, "Vi / V:i"],
                21=> [2, "uv / v / v; iv / i / j"],
                22=> [2, "v / 0"],
                23=> [2, "uv / vv"],
                24=> [2, "uo / ua; yö / yä"],
                25=> [2, "Vv / V:v"],
                26=> [2, "j / 0"],
                27=> [2, "ll / l, rr / r Чередование"],
                28=> [2, "Гемната после C"],
                29=> [2, "Колич. чередование"],
                30=> [2, "Чередование st, sk (1)"],
                31=> [2, "Чередование st, sk (2)"],
                32=> [2, "Чередование hk, ht"],
                33=> [2, "Чередование tk"],
                34=> [2, "Чередование mb, nd, ld, rd"],
                35=> [2, "Чередование g, d, b"],
                36=> [2, "s в нач. перед a"],
                37=> [2, "s в нач. перед e"],
                38=> [2, "s в нач. перед i"],
                39=> [2, "s в нач. перед o"],
                40=> [2, "s в нач. перед y, äää"],
                41=> [2, "s в нач. слова"],
                42=> [2, "s в середине слова"],
                43=> [2, "s в конце слова"],
                44=> [2, "č / c"],
                45=> [2, "t / t’ в начале слова"],
                46=> [2, "t / t’,d / d’ в середине слова"],
                47=> [2, "t / t’ на конце слова"],
                48=> [2, "n / n’ в начале слова"],
                49=> [2, "n / n’ в середине сова"],
                50=> [2, "n / n’ на конце слова"],
                51=> [2, "r / r’ в начале слова"],
                52=> [2, "r / r’ в середине слова"],
                53=> [2, "l / l’ в начале слова"],
                54=> [2, "l / l’ в середине слова"],
                55=> [2, "l / l’ на конце слова"],
                56=> [2, "n / 0 на конце слова"],
                57=> [2, "nh / hn"],
                58=> [2, "l / u"],
                59=> [3, "генитив"],
                60=> [3, "аккузатив"],
                61=> [3, "эссив"],
                62=> [3, "партитив"],
                63=> [3, "транслатив"],
                64=> [3, "инессив"],
                65=> [3, "элатив"],
                66=> [3, "иллатив"],
                67=> [3, "адессив"],
                68=> [3, "абатив"],
                69=> [3, "аллатив"],
                70=> [3, "абессив"],
                71=> [3, "инструктив"],
                72=> [3, "комитатив"],
                73=> [3, "аппроксиматив"],
                74=> [3, "эгрессив"],
                75=> [3, "К кому? К чему?"],
                76=> [3, "О ком? О чем?"],
                77=> [3, "терминатив"],
                78=> [3, "притяжательные суффиксы"],
                79=> [3, "суперлатив"],
                80=> [3, "3 л. ед. през. инд."],
                81=> [3, "1, 2 л. мн. през. инд."],
                82=> [3, "3 л. мн. през. инд."],
                83=> [3, "1, 2 ед. имп. инд."],
                84=> [3, "3 л. ед. имп. инд."],
                85=> [3, "1, 2 л. мн. имп. инд."],
                86=> [3, "3 л. мн. имп. инд."],
                87=> [3, "3 л. ед. през. пот."],
                88=> [3, "3 л. мн. през. пот."],
                89=> [3, "1 л. ед. през. конд."],
                90=> [3, "3 л. ед. през. конд."],
                91=> [3, "1, 2 л. мн. през. конд."],
                92=> [3, "3 л. мн. през. конд."],
                93=> [3, "3 л. имп. конд."],
                94=> [3, "1 л. мн. импер."],
                95=> [3, "2 л. мн. импер."],
                96=> [3, "3 л. импер."],
                97=> [3, "отриц."],
                98=> [3, "3 л. мн. през. отр."],
                99=> [3, "имп. отр."],
                100=>[3, "1, 2 л. имп. отр."],
                101=>[3, "3 л. мн. имп. отр."],
                102=>[3, "Импер. отр."],
                103=>[3, "I   инф."],
                104=>[3, "II инф."],
                105=>[3, "II прич. пасс. парт."],
                106=>[3, "III инф."],
                107=>[3, "I прич. акт."],
                108=>[3, "II прич. акт."],
                109=>[3, "II прич. пасс."],
                110=>[3, "Рефлексивное спряжение"],
                111=>[3, "начинат."],
                112=>[4, "существительные"],
                113=>[4, "прилагательные"],
                114=>[4, "наречия"],
                115=>[4, "глаголы"],
              ];
        foreach ($subsections as $subsection_id => $subsection) {
            Qsection::create(['id'=>$subsection_id, 'parent_id'=>$subsection[0], 'title'=>$subsection[1]]);
        }
    }
    
}
