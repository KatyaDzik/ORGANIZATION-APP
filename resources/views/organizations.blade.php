@extends('layouts.app')

@section('doc-title')
Organizations
@endsection

@section('content')
<div class="container">
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
      @foreach($data as $el)
        <tr>
          <td>{{$el->name}}</td>
          <td>{{$el->ogrn}}</td>
          <td>{{$el->oktmo}}</td>
          <td><a href="#"><button class="btn btn-warning">Детальнее</button></a></td>
        </tr>
      @endforeach
      </tbody>
      </table>
    </div>
@endsection