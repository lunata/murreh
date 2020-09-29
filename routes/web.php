<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

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