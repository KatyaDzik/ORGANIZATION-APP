@extends('layouts.app')

@section('doc-title')
    {{$data->last_name}} {{$data->first_name}} {{$data->middle_name}}
@endsection
<!-- Вывод информации о организации -->
@section('content')
   <div class="container">
       <h1>{{$data->last_name}} {{$data->first_name}} {{$data->middle_name}} </h1>
       <h4>Дата рождения: {{$data->birthday}}</h4>
       <h4>ИНН: {{$data->inn}}</h4>
       <h4>СНИЛС: {{$data->snils}}</h4>
       <h4>Название организации : {{$data->name}}</h4>
   </div>
@endsection



