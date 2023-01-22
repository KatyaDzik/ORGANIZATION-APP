@extends('layouts.app')

@section('doc-title')
Organizations
@endsection

<!-- Вывод информации о организации -->
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
          @if(count($orgs)>0)
      @foreach($orgs as $el)
        <tr>
          <td>{{$el->name}}</td>
          <td>{{$el->ogrn}}</td>
          <td>{{$el->oktmo}}</td>
          <td><a href="{{route('org-data-by-id',  $el->id)}}"><button class="btn btn-warning">Просмотр</button></a></td>
          <td><a href="{{route('delete-org-by-id',  $el->id)}}"><button class="btn btn-danger">Удалить</button></a></td>
        </tr>
      @endforeach

      </tbody>
      </table>
    <div>{{$orgs->links()}}</div>
    @endif
    </div>
@endsection
