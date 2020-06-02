<?php


namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function getProfile($email){
        $user = User::where('email', $email)->first();
        if (!$user){
            abort(404);
        }
        $status = $user->status()->notReply()->get();

        return view('profile.index')
            ->with('user', $user)
            ->with('status', $status)
            ->with('authUserIsFriend', Auth::user()->isFriendsWith($user));
    }

    public function postEdit(Request $request){
        $this->validate($request, [
            'nome'=>'alpha|max:30',
            'sobrenome'=>'alpha|max:30',
        ]);

        Auth::user()->update([
            'nome' => $request->input('nome'),
            'sobrenome' => $request->input('sobrenome'),
            'localizacao'=>$request->input('localizacao'),
            'faculdade'=>$request->input('faculdade'),
            'curso'=>$request->input('curso'),
            'nasceuEm'=>$request->input('nasceuEm'),
            'trabalho'=>$request->input('trabalho'),
            'relacionamento'=>$request->input('relacionamento'),
        ]);

        return redirect()
            ->route('profile.index', ['email'=>Auth::user()->email])
            ->with('info', 'Seu perfil foi atualizado!');
    }
}
