<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\Geo\DistrictController;
use App\Http\Controllers\Geo\RegionController;
use App\Http\Controllers\Geo\PlaceController;

use App\Http\Controllers\Person\NationalityController;
use App\Http\Controllers\Person\OccupationController;
use App\Http\Controllers\Person\RecorderController;
use App\Http\Controllers\Person\InformantController;

use App\Http\Controllers\Ques\AnketaController;
use App\Http\Controllers\Ques\AnketaQuestionController;
use App\Http\Controllers\Ques\AnswerController;
use App\Http\Controllers\Ques\QsectionController;
use App\Http\Controllers\Ques\QuestionController;

use App\Http\Controllers\Library\ImportController;
use App\Http\Controllers\Library\ServiceController;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

// Вызов страницы регистрации пользователя
Route::get('register', [AuthController::class, 'register']);   
// Пользователь заполнил форму регистрации и отправил
Route::post('register', [AuthController::class, 'registerProcess']); 
// Пользователь получил письмо для активации аккаунта со ссылкой сюда
Route::get('activate/{id}/{code}', [AuthController::class, 'activate']);
// Вызов страницы авторизации
Route::get('login', [AuthController::class, 'login'])->name('login');
// Пользователь заполнил форму авторизации и отправил
Route::post('login', [AuthController::class, 'loginProcess']);
// Выход пользователя из системы
Route::get('logout', [AuthController::class, 'logoutuser']);
// Пользователь забыл пароль и запросил сброс пароля. Это начало процесса - 
// Страница с запросом E-Mail пользователя
Route::get('reset', [AuthController::class, 'resetOrder']);
// Пользователь заполнил и отправил форму с E-Mail в запросе на сброс пароля
Route::post('reset', [AuthController::class, 'resetOrderProcess']);
// Пользователю пришло письмо со ссылкой на эту страницу для ввода нового пароля
Route::get('reset/{id}/{code}', [AuthController::class, 'resetComplete']);
// Пользователь ввел новый пароль и отправил.
Route::post('reset/{id}/{code}', [AuthController::class, 'resetCompleteProcess']);
// Сервисная страничка, показываем после заполнения рег формы, формы сброса и т.
// о том, что письмо отправлено и надо заглянуть в почтовый ящик.
Route::get('wait', [AuthController::class, 'wait']);

Route::get('geo/place/map', [PlaceController::class, 'showMap']);

//Route::get('import/district_names', [ImportController::class, 'districtNames']);
Route::get('import/place_coord', [ImportController::class, 'placeCoord']);
Route::get('import/qsections', [ImportController::class, 'qsections']);
Route::get('import/questions', [ImportController::class, 'questions']);

Route::get('ques/anketa_question/{anketa_id}_{qsection_id}/edit', [AnketaQuestionController::class, 'edit'])->name('anketa_question.edit');
Route::put('ques/anketa_question/{id}', [AnketaQuestionController::class, 'update'])->name('anketa_question.update');
Route::get('ques/anketa_question/compare_anketas', [AnketaQuestionController::class, 'compareAnketas']);
Route::get('ques/qsection/list', [QsectionController::class, 'qsectionList']);

Route::get('service', [ServiceController::class, 'index']);
Route::get('service/add_sequence_number_to_qsections', [ServiceController::class, 'addSequenceNumberToQsections']);
Route::get('service/add_sequence_number_to_questions', [ServiceController::class, 'addSequenceNumberToQuestions']);
Route::get('service/split_qsections', [ServiceController::class, 'splitQsections']);

Route::resources([
    'geo/district' => DistrictController::class,
    'geo/region'   => RegionController::class,
    'geo/place'    => PlaceController::class,
    
    'person/nationality' => NationalityController::class,
    'person/occupation'  => OccupationController::class,
    'person/recorder'    => RecorderController::class,
    'person/informant'   => InformantController::class,
    
    'ques/anketas'      => AnketaController::class,
    'ques/answer'      => AnswerController::class,
    'ques/qsection'    => QsectionController::class,
    'ques/question'    => QuestionController::class,
    
    'role'  => RoleController::class,
    'user'  => UserController::class,
]);
