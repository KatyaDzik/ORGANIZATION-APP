@extends('layouts.app')

@section('doc-title')
Organizations
@endsection

@section('content')
<div class="container">
<form action="{{route('add-user-form')}}" method="post">
    @csrf

  <div class="form-group">
    <label for="lastName">Фамилия</label>
    <input type="text" class="form-control" id="lastName" name="lastName"  placeholder="Фамилия">
  </div>

  <div class="form-group">
    <label for="firstName">Имя</label>
    <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Имя">
  </div>

  <div class="form-group">
    <label for="middlename">Отчество</label>
    <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Отчество">
  </div>

  <div class="form-group">
    <label for="date">Дата Рождения</label>
    <input type="date" class="form-control" id="date" name="date" placeholder="Дата рождения">
  </div>

  <div class="form-group">
    <label for="inn">ИНН</label>
    <input type="number" class="form-control" id="inn" name="inn" placeholder="ИНН">
  </div>

  <div class="form-group">
    <label for="snils">СНИЛС</label>
    <input type="number" class="form-control" id="snils" name="snils" placeholder="ИНН">
  </div>
  
  <button type="submit" class="btn btn-success">отправить</button>
</form>
</div>
@endsection
