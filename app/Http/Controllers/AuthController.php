<?php
namespace App\Http\Controllers;
use App\Services\AuthJsonService; use Illuminate\Http\Request;
class AuthController extends Controller {
 public function registerForm(){return view('auth.register');}
 public function register(Request $r,AuthJsonService $auth){$d=$r->validate(['nome_loja'=>'required|max:100','nome_responsavel'=>'required|max:100','telefone'=>'required|min:10','email'=>'required|email','senha'=>'required|min:6|confirmed','chave_pix'=>'nullable|max:120','cidade'=>'required','estado'=>'required|max:2']); [$loja,$user]=$auth->register($d);$this->loginSession($loja,$user);return redirect()->route('dashboard')->with('success','Loja cadastrada. Bem-vindo ao Meu Fiado!');}
 public function loginForm(){return view('auth.login');}
 public function login(Request $r,AuthJsonService $auth){$d=$r->validate(['email'=>'required|email','senha'=>'required']);$found=$auth->attempt($d['email'],$d['senha']);if(!$found)return back()->withInput()->with('error','E-mail ou senha inválidos.');[$loja,$user]=$found;$this->loginSession($loja,$user);return redirect()->intended(route('dashboard'));}
 private function loginSession(array $loja,?array $user):void {session(['loja_id'=>$loja['id'],'usuario_id'=>$user['id']??$loja['id'],'nome_loja'=>$loja['nome_loja'],'perfil'=>$user['perfil']??'dono']);session()->regenerate();}
 public function logout(Request $r){$r->session()->invalidate();$r->session()->regenerateToken();return redirect()->route('home');}
}
