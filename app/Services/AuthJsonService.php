<?php
namespace App\Services;
use App\Repositories\{LojaRepository,UsuarioRepository}; use Illuminate\Support\Facades\Hash;
class AuthJsonService {
 public function __construct(private LojaRepository $lojas, private UsuarioRepository $usuarios) {}
 public function register(array $data): array { $loja=$this->lojas->create(array_merge($data,['senha_hash'=>Hash::make($data['senha']),'plano'=>'Básico','ativo'=>true,'configuracoes'=>['bloquear_limite'=>true,'dias_atraso'=>30,'saldo_bloqueio'=>0]])); $dono=$this->usuarios->create(['loja_id'=>$loja['id'],'nome'=>$data['nome_responsavel'],'email'=>$data['email'],'senha_hash'=>$loja['senha_hash'],'perfil'=>'dono','ativo'=>true]); return [$loja,$dono]; }
 public function attempt(string $email,string $senha): ?array { foreach($this->lojas->all() as $loja) if(strtolower($loja['email'])===strtolower($email) && ($loja['ativo']??false) && Hash::check($senha,$loja['senha_hash'])) { $user=collect($this->usuarios->where('loja_id',$loja['id']))->firstWhere('perfil','dono'); return [$loja,$user]; } return null; }
}
