<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,AppController,ClienteController,MovimentacaoController};
Route::view('/', 'welcome')->name('home');
Route::get('/cadastro-loja',[AuthController::class,'registerForm'])->name('register');
Route::post('/cadastro-loja',[AuthController::class,'register']);
Route::get('/login',[AuthController::class,'loginForm'])->name('login'); Route::post('/login',[AuthController::class,'login']);
Route::get('/cliente/consulta/{token}',[AppController::class,'publicClient'])->name('client.public');
Route::middleware('json.auth')->prefix('app')->group(function(){
 Route::post('/sair',[AuthController::class,'logout'])->name('logout'); Route::get('/dashboard',[AppController::class,'dashboard'])->name('dashboard');
 Route::get('/clientes',[ClienteController::class,'index'])->name('clients.index');Route::get('/clientes/criar',[ClienteController::class,'create'])->name('clients.create');Route::post('/clientes',[ClienteController::class,'store'])->name('clients.store');Route::get('/clientes/{id}',[ClienteController::class,'show'])->name('clients.show');Route::get('/clientes/{id}/editar',[ClienteController::class,'edit'])->name('clients.edit');Route::post('/clientes/{id}/atualizar',[ClienteController::class,'update'])->name('clients.update');Route::post('/clientes/{id}/bloquear',[ClienteController::class,'block'])->name('clients.block');
 Route::get('/balcao',[AppController::class,'balcao'])->name('counter');Route::get('/lancamentos/criar',[MovimentacaoController::class,'createLaunch'])->name('launch.create');Route::post('/lancamentos',[MovimentacaoController::class,'storeLaunch'])->name('launch.store');Route::get('/pagamentos/criar',[MovimentacaoController::class,'createPay'])->name('payment.create');Route::post('/pagamentos',[MovimentacaoController::class,'storePay'])->name('payment.store');Route::get('/comprovante/{tipo}/{id}',[MovimentacaoController::class,'receipt'])->name('receipt');
 Route::get('/inadimplentes',[AppController::class,'inadimplentes'])->name('overdue');Route::get('/relatorios',[AppController::class,'reports'])->name('reports');Route::get('/assistente-cobranca',[AppController::class,'assistant'])->name('assistant');Route::get('/usuarios',[AppController::class,'users'])->name('users');Route::get('/configuracoes',[AppController::class,'config'])->name('config');Route::post('/configuracoes',[AppController::class,'updateConfig']);Route::get('/clientes/{id}/whatsapp',[AppController::class,'whatsapp'])->name('clients.whatsapp');
});
