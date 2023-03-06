<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\Dict\DialectController;
use App\Http\Controllers\Dict\LangController;

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

use App\Http\Controllers\Library\ExportController;
use App\Http\Controllers\Library\ImportController;
use App\Http\Controllers\Library\ServiceController;

use App\Http\Controllers\Library\Experiments\ClusterizationController;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\SOSD\ConceptController;
use App\Http\Controllers\SOSD\ConceptCategoryController;
use App\Http\Controllers\SOSD\ConceptPlaceController;

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

Route::get('dict/dialect/list', [LangController::class, 'dialectList']);

Route::get('experiments', function () {
    return view('experiments.index');
});
Route::get('experiments/{data}_cluster/', [ClusterizationController::class, 'index']);
Route::get('experiments/{data}_cluster/view_data', [ClusterizationController::class, 'viewData']);
Route::get('experiments/{data}_cluster/example/{example_id}', [ClusterizationController::class, 'exampleFromFile']);
Route::get('experiments/{data}_cluster/export_data_for_dendrogram', [ClusterizationController::class, 'exportDataForDendrogram']);
Route::get('experiments/{data}_cluster/export_example', [ClusterizationController::class, 'exportExample']);


Route::get('export/answers_by_questions', [ExportController::class, 'answersByQuestions']);
Route::get('export/concepts', [ExportController::class, 'concepts']);
Route::get('export/concepts_by_places', [ExportController::class, 'conceptsByPlaces']);
Route::get('export/translations_by_questions', [ExportController::class, 'translationsByQuestions']);

Route::get('geo/place/list', [PlaceController::class, 'placeList']);
//Route::get('geo/place/map', [PlaceController::class, 'showMap']);

/*Route::get('import/place_coord', [ImportController::class, 'placeCoord']);
Route::get('import/qsections', [ImportController::class, 'qsections']);
Route::get('import/questions', [ImportController::class, 'questions']);
Route::get('import/concepts', [ImportController::class, 'concepts']);
Route::get('import/concept_categories', [ImportController::class, 'conceptСategories']);
*/
Route::get('import/concept_place', [ImportController::class, 'conceptPlace']);

Route::get('ques/anketa/list', [AnketaController::class, 'anketaList']);
Route::get('ques/anketa/map', [AnketaController::class, 'onMap']);

Route::get('ques/anketa_question/{anketa_id}_{qsection_id}/edit', [AnketaQuestionController::class, 'edit'])->name('anketa_question.edit');
Route::get('ques/anketa_question/copy/{from_anketa}_{to_anketa}_{qsection_id}', [AnketaQuestionController::class, 'copyAnswers']);
Route::get('ques/anketa_question/list_for_copy/{anketa_id}_{qsection_id}', [AnketaQuestionController::class, 'listForCopy']);
Route::put('ques/anketa_question/{anketa_id}', [AnketaQuestionController::class, 'update'])->name('anketa_question.update');
Route::get('ques/anketa_question/compare_anketas', [AnketaQuestionController::class, 'compareAnketas']);

Route::get('ques/answer/list', [AnswerController::class, 'answerList']);

Route::get('ques/qsection/{id}/map/{map_number}', [QsectionController::class, 'map']);
Route::get('ques/qsection/{id}/visible/{status}', [QsectionController::class, 'changeVisible']);
Route::get('ques/qsection/list', [QsectionController::class, 'qsectionList']);

Route::get('ques/question/{id}/edit_answer/{anketa_id}', [QuestionController::class, 'editAnswer']);
Route::get('ques/question/{id}/map', [QuestionController::class, 'onMap']);
Route::get('ques/question/copy/{from_question_id}_{to_qsection}', [QuestionController::class, 'copy']);
Route::get('ques/question/store_from_cluster', [QuestionController::class, 'storeFromCluster']);
Route::get('ques/question/list', [QuestionController::class, 'questionList']);
Route::put('ques/question/update_answer/{anketa_id}', [QuestionController::class, 'updateAnswer'])->name('question.update_answer');

Route::get('service', [ServiceController::class, 'index']);
Route::get('service/add_sequence_number_to_qsections', [ServiceController::class, 'addSequenceNumberToQsections']);
Route::get('service/add_sequence_number_to_questions', [ServiceController::class, 'addSequenceNumberToQuestions']);
Route::get('service/replace_apostroph', [ServiceController::class, 'replaceApostroph']);
Route::get('service/split_qsections', [ServiceController::class, 'splitQsections']);
Route::get('service/merge_answers', [ServiceController::class, 'mergeAnswers']);
Route::get('service/remove_empty_question_numbers', [ServiceController::class, 'removeEmptyQuestionNumbers']);

Route::get('sosd/concept/list', [ConceptController::class, 'conceptList']);
Route::get('sosd/concept/{id}/map', [ConceptController::class, 'onMap']);

Route::get('sosd/concept_category/list', [ConceptCategoryController::class, 'categoryList']);
Route::get('sosd/concept_category/{id}/map/{map_number}', [ConceptCategoryController::class, 'map']);

Route::get('sosd/concept_place/compare_vocs', [ConceptPlaceController::class, 'compareVocs']);
Route::get('sosd/concept_place', [ConceptPlaceController::class, 'index']);
Route::put('sosd/concept_place/{id}', [ConceptPlaceController::class, 'update'])->name('concept_place.update');
Route::get('sosd/concept_place/{concept_id}_{next_count}/edit_voc', [ConceptPlaceController::class, 'editVoc'])->name('concept_place.edit_voc');
Route::get('sosd/concept_place/{place_id}', [ConceptPlaceController::class, 'show']);
Route::get('sosd/concept_place/{place_id}_{category_id}/edit', [ConceptPlaceController::class, 'edit'])->name('concept_place.edit');

Route::resources([
    'dict/dialect' => DialectController::class,
    
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
    'sosd/concept'  => ConceptController::class,
    'sosd/concept_category'  => ConceptCategoryController::class,
    'user'  => UserController::class,
]);
