@extends('layouts.app')

@section('doc-title')
{{$data->name}}
@endsection

@section('content')
<div class="container">
    <h1>{{$data->name}}</h1>
    <h4>ОГРН: {{$data->ogrn}}</h44>
    <h4>ОКТМО: {{$data->oktmo}}</h4> 
    <h2 style="margin-top: 100px">Пользователи</h2>
    <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">ФИО</th>
              <th scope="col">Дата рождения</th>
              <th scope="col">ИНН</th>
              <th scope="col">СНИЛС</th>
            </tr>
          </thead>
          <tbody>
    @if (count($users) > 0)        
      @foreach($users as $el)
        <tr>
          <td>{{$el->last_name}} {{$el->first_name}} {{$el->middle_name}} </td>
          <td>{{$el->birtday}}</td>
          <td>{{$el->inn}}</td>
          <td>{{$el->snils}}</td>
        </tr>
      @endforeach
    @endif
      </tbody>
      </table>
</div>



@endsection
