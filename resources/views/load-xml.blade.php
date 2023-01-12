@extends('layouts.app')

@section('doc-title')
    Organizations
@endsection

@section('content')
    <div class="container">
        <form action="{{route('add-user-form')}}" method="post">
            @csrf
            <div class="form-group" style="margin: 50px">
                <label for="file">Выберете файл для загрузки</label>
                <input type="file" required class="form-control" accept=".xml"id="file" name="file" multiple>
            </div>
            <button type="submit" class="btn btn-success">отправить</button>
        </form>
    </div>
@endsection
