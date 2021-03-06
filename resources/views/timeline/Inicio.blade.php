@extends('templates.default')
@section('pageTitle', 'Inicio')
@section('conteudo')
<style>
/* CSS GERAL DA PAGINA */
#conteudoTimeline {
    margin-top: 20px;
    color: var(--primaryText-color);
    margin-left: -36px;
}

@media only screen and (max-width: 725px) {
    .conteudoCarregado {
        margin-top: 45px;
        width: 90%;
        margin-left: 25px;
        margin-bottom: 50px;
    }

    .status {
        margin-left: 10px;
    }
}

/* CSS DO 'FAZER POSTAGEM' */
#postarStatus {
    background-color: var(--card-color)
}

/* CSS DA TEXTAREA GERAL */
.textarea,
.textarea::placeholder,
.textarea:focus {
    background: var(--card-color);
    border: none;
    color: var(--primaryText-color);
}

/* CSS DOS STATUS CARREGADOS */
.status a {
    color: var(--primaryText-color);
}

.status {
    margin-left: 10px;
    margin-bottom: 5px;
    padding: 0;
    border: 1px solid var(--secondaryText-color);
    border-radius: 4px;
    background-color: var(--card-color);
}

.like {
    color: white;
}

/* CSS GERAL DA FOTO DOS STATUS */
.foto {
    border-radius: 50px;
    width: 60px;
}

/* CSS DA LISTA 'CURTIR','X CURTIDAS' */
ul#opcoes li {
    display: inline;
    margin: 0;
}

/* CSS DO 'POSTADO A X MIN/HR/DIAS' */
.secondText {
    color: var(--secondaryText-color);
}

/* CSS DO BOTAO DE 'VER COMENTARIO' */
.verComentario {
    margin-bottom: 10px;
}

/* CSS DE TODOS OS BOTÕES*/
.btn {
    background-color: var(--secondary-color);
    border: solid 1px var(--font-color);
}

.btn:hover,
.btn:active {
    background-color: var(--primary-color);
    border: solid 1px var(--font-color);
}

#cssSubImg{
    padding: 10px;
    width: auto;
    color: white;
    font-family: 'Josefin Sans', sans-serif;
}
</style>

<!-- POSTAR STATUS -->
<div id="conteudoTimeline" class="row">
    <div class="status col-sm-12 col-lg-12">
        <form role="form" action="{{route('status.post')}}" method="post">
            <div class='card' id="postarStatus">
                <div class='card-body'>
                    <textarea required class="textarea form-control" name='status' id='post' rows='2'
                        placeholder='  Quer dizer algo?'></textarea>
                </div>
                <div class='card-footer'>
                    <button type='submit' class='btn'>Postar</button>
                    <input type="file" id="cssSubImg" name="postImage"><br>
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                </div>
            </div>
            <input type="hidden" name="_token" value="{{Session::token()}}">
        </form>
    </div>
    <!-- OUTROS STATUS -->
    @if(!$status->count())
    <p>Ainda não houve postagens.</p>
    @else
    <!-- CARREGA TODOS OS STATUS POSTADOS -->
    @foreach($status as $post)
    @if(Auth::user()->isFriendsWith($post->user) || Auth::user()->id===$post->user->id)
    <div class="status card col-sm-12 col-lg-12">
        <div class='card-body'>
            <div class="media">
                <div class="media-body">
                    @include('user.partials.userbasic')
                    <hr>
                    <p>{{$post->body}}</p>
                    <ul class="list-inline" id="opcoes">
                        <span hidden>{{\Carbon\Carbon::setLocale('pt_BR')}}</span>
                        <li>{{$post->created_at->diffForHumans()}} &nbsp;</li>
                        <li class="likeCount-{{$post->id}}" value="{{$post->id}}">
                            {{$post->likes->count()}}
                        </li> <i class="fas fa-heart"></i>
                        @if($post->user->id !== Auth::user()->id)
                        <br>
                        <li>
                            <button type="button" class="like btn btn-sm" value="{{$post->id}}">
                                <i class="fas fa-heart"></i> Curtir
                            </button>
                        </li>
                        @endif
                    </ul>
                    {{--RESPOSTA DO STATUS --}}
                    @if($post->replies->count()>0)
                    <button class="verComentario btn btn-sm" type="button" data-toggle="collapse"
                        data-target=".collapse " aria-expanded="false" aria-controls="collapseExample">
                        Ver Comentários
                    </button>
                    @endif
                    @foreach($post->replies as $reply)
                    <div class="collapse media">
                        <a class="pull-left" href="{{route('profile.Perfil', ['username' => $reply->user->username])}}">
                            <img class="foto media-object" alt="{{$reply->user->getName()}}"
                                src="{{URL::asset("uploads/avatars/{$reply->user->avatar}")}}">
                        </a>
                        <div class="media-body">
                            <h6>
                                <a
                                    href="{{route('profile.Perfil', ['username' => $reply->user->username])}}">{{$reply->user->getName()}}</a>
                            </h6>
                            <p>{{$reply->body}}</p>
                            <ul class="list-inline" id="opcoes">
                                <span hidden>{{\Carbon\Carbon::setLocale('pt_BR')}}</span>
                                <li>{{$reply->created_at->diffForHumans()}} &nbsp;</li>
                                <li class="likeCount-{{$reply->id}}" value="{{$reply->id}}">
                                    {{$reply->likes->count()}}
                                </li><i class="fas fa-heart"></i>
                                <br>
                                @if($reply->user->id !== Auth::user()->id)
                                <button type="button" class="like btn btn-sm" value="{{$reply->id}}">
                                    <i class="fas fa-heart"></i> Curtir
                                </button>
                                @endif
                            </ul>
                        </div>
                    </div>
                    @endforeach
                    <!-- FAZER COMENTÁRIO -->
                    @if(Auth::user()->isFriendsWith($post->user) || Auth::user()->id===$post->user->id)
                    <form role="form" action="{{route('status.reply', ['statusId' => $post->id])}}" method="post">
                        <div class="form-group {{$errors->has("reply-{$post->id}") ? 'has-error':''}}">
                            <textarea name="reply-{{$post->id}}" class="textarea form-control" rows="2"
                                placeholder="Comente sobre..."></textarea>
                            @if($errors->has("reply-{$post->id}"))
                            <span class="help-block">
                                {{$errors->first("reply-{$post->id}")}}
                            </span>
                            @endif
                        </div>
                        <input type="submit" value="Comentar" class="btn btn-sm">
                        <input type="hidden" name="_token" value="{{Session::token()}}">
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
    @endforeach
    @endif
</div>
<br>
{!!$status->render()!!}
<br>
@stop