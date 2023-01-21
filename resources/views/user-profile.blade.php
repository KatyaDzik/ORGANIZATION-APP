@extends('layouts.app')

@section('doc-title')
    {{$user->last_name}} {{$user->first_name}} {{$user->middle_name}}
@endsection

<!-- Вывод информации о пользователе -->
@section('content')
   <div class="container">
       <h1>{{$user->last_name}} {{$user->first_name}} {{$user->middle_name}} </h1>
       <h4>Дата рождения: {{$user->birthday}}</h4>
       <h4>ИНН: {{$user->inn}}</h4>
       <h4>СНИЛС: {{$user->snils}}</h4>

       @if (!empty($organizations))
           <h1>Организации</h1>
           <table class="table table-striped table-sm">
               <thead>
               <tr>
                   <th scope="col">Наименование</th>
                   <th scope="col">ОГРН</th>
                   <th scope="col">ОКТМО</th>
                   <th scope="col"></th>
               </tr>
               </thead>
               <tbody>
                   @foreach($organizations as $el)
                       <tr>
                           <td>{{$el->name}}</td>
                           <td>{{$el->ogrn}}</td>
                           <td>{{$el->oktmo}}</td>
                           <td><a href="{{route('org-data-by-id',  $el->id)}}"><button class="btn btn-warning">Просмотр</button></a></td>
                       </tr>
                   @endforeach

               </tbody>
           </table>
       @endif
   </div>
@endsection



