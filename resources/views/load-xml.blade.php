@extends('layouts.app')

@section('doc-title')
    Загрузка данных
@endsection

@section('content')
    <div class="container">
{{--  Вывод ошибок  --}}
        @if(isset($xml_error))
            <br>
            <div class="alert alert-danger">
                <h4>Файл XML не валидный</h4>
                <h5>{{$xml_error}}</h5>
            </div>
        @endif

    @if(isset($mess_error))
        <br>
        <div class="alert alert-danger">
            <ul>
                <h4>Допущены ошибки</h4>
                @foreach($mess_error->all() as $mes)
                    <li> {{$mes}}</li>
                @endforeach
            </ul>
            <ul>
            <br/><h4>Обратите внимание на объект</h4>
            @foreach($obj_err as $item)
                <li> {{$item}}</li>
            @endforeach
            </ul>
        </div>
    @endif

        @if(isset($success_msg))
            <br>
            <div class="alert alert-success">
                <h4>{{$success_msg}}</h4>
            </div>
        @endif

{{--  Форма для загрузки файка XML  --}}
        <form action="{{route('load-data-from-xml')}}" enctype="multipart/form-data" method="post">
            @csrf
            <div class="form-group" style="margin: 50px">
                <label for="file">Выберете файл для загрузки</label>
                <input type="file" required class="form-control" accept=".xml" id="file" name="file">
            </div>
            <button type="submit" class="btn btn-success">отправить</button>
        </form>
    </div>
@endsection
