@extends('layouts.app')

@section('doc-title')
{{$data->name}}
@endsection

    <!-- Вывод информации о организации -->
    @section('content')
    <div class="container">
    <h1>{{$data->name}}</h1>
    <h4>ОГРН: {{$data->ogrn}}</h4>
    <h4>ОКТМО: {{$data->oktmo}}</h4>
<!-- модальное окно с формой добавления -->
{{--    <button class="btn btn-success">Добавить</button>--}}
    <div id="myModal" class="mymodal">
        @if($errors->any())
            <div class="alert alert-danger">
            <ul>
                @foreach($obj_err->all() as $item)
                    <li> {{$item}}</li>
                @endforeach
            </ul>
            </div>
        @endif

      <form style="margin: 20px;" action="/org/{{$data->id}}/adduser" method="post">
        @csrf
        <div class="form-group">
          <label for="lastname">Фамилия</label>
          <input type="text" class="form-control" value="{{ old('lastname') }}" id="lastname" name="lastname"  placeholder="Фамилия">
        </div>

        <div class="form-group">
          <label for="firstname">Имя</label>
          <input type="text" class="form-control" value="{{ old('firstname') }}" id="first_name" name="firstname" placeholder="Имя">
        </div>

        <div class="form-group">
          <label for="middlename">Отчество</label>
          <input type="text" class="form-control" id="middlename" name="middlename" value="{{ old('middlename') }}" placeholder="Отчество">
        </div>

        <div class="form-group">
          <label for="birthday">Дата Рождения</label>
          <input type="date" class="form-control" id="birthday" name="birthday" value="{{ old('birthday') }}" placeholder="Дата рождения">
        </div>

        <div class="form-group">
          <label for="inn">ИНН</label>
          <input type="number" class="form-control" id="inn" name="inn" value="{{ old('inn') }}" placeholder="ИНН">
        </div>

        <div class="form-group">
          <label for="snils">СНИЛС</label>
          <input type="number" class="form-control" id="snils" name="snils" value="{{ old('snils') }}" placeholder="СНИЛС">
        </div>

        <div class="modal-footer">
              <button type="button" class="btn btn-secondary">Закрыть</button>
              <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
      </form>
    </div>



   <!-- вывод пользователей, которые есть в организации-->

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
          <td>{{$el->birthday}}</td>
          <td>{{$el->inn}}</td>
          <td>{{$el->snils}}</td>
            <td><a href="{{route('user-data-by-id',  $el->id)}}"><button class="btn btn-warning">Детальнее</button></a></td>
        </tr>
      @endforeach
    @endif
      </tbody>
      </table>

    </div>




@endsection


