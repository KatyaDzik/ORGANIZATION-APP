@extends('layouts.app')

@section('doc-title')
{{$data->name}}
@endsection

    <!-- Вывод информации о организации -->
    @section('content')
    <div class="container">
        <div style="display: flex; padding: 20px 20px 20px 0;">
            <h1 style="margin: 0; margin-right: 20px;">{{$data->name}}</h1>
            <button class="pen" id="btnOpenUpdateModalOrg"><img width="20px" src="{{ URL::asset('img/pen.png') }}" alt=""></button>
        </div>
    <h4>ОГРН: {{$data->ogrn}}</h4>
    <h4>ОКТМО: {{$data->oktmo}}</h4>

        <!-- Форма для изменения данных об организации -->
        <div id="formOrgUpdateModal" class="mymodal">

            <form style="margin: 20px;"  class="form_modal" action="/org/{{$data->id}}/edit" method="post">
                {{--     Блок для вывода ошибок       --}}
                <div style="display: none" id="updateOrgError" class="alert alert-danger"></div>
                {{--         Форма       --}}
                @csrf
                <div class="form-group">
                    <label for="name">Название</label>
                    <input type="text" class="form-control" value="{{ $data->name }}" id="name" name="name" >
                </div>

                <div class="form-group">
                    <label for="ogrn">ОГРН</label>
                    <input type="text" class="form-control" value="{{ $data->ogrn }}" id="ogrn" name="ogrn">
                </div>

                <div class="form-group">
                    <label for="oktmo">ОКТМО</label>
                    <input type="text" class="form-control" id="oktmo" name="oktmo" value="{{ $data->oktmo }}">
                </div>

                <div class="modal-footer">
                    <button type="button" id="btnCloseUpdateModalOrg" class="btn btn-secondary">Закрыть</button>
                    <button type="submit" id="updateOrg" value="{{$data->id}}" class="btn btn-primary">Обновить</button>
                </div>
            </form>
        </div>


   <!-- вывод пользователей, которые есть в организации-->
        @if (!empty($users))
            <div style="display: flex;   justify-content: space-between; padding-top: 100px;">
                <h2 style="margin-top: 10px">Пользователи</h2>
                <button id="btnOpenModal" class="btn btn-success">Добавить</button></div>
            <!-- модальное окно с формой добавления -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $message)
                            <li> {{$message}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif



            <div id="myModal" class="mymodal">

                <form style="margin: 20px;"  class="form_modal" action="/org/{{$data->id}}/adduser" method="post">
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
                        <button type="button" id="btnCloseModal" class="btn btn-secondary">Закрыть</button>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>


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
      @foreach($users as $el)
        <tr>
          <td>{{$el->last_name}} {{$el->first_name}} {{$el->middle_name}} </td>
          <td>{{$el->birthday}}</td>
          <td>{{$el->inn}}</td>
          <td>{{$el->snils}}</td>
            <td><a href="{{route('user-data-by-id',  $el->id)}}"><button class="btn btn-warning">Просмотр</button></a></td>
        </tr>
      @endforeach
    @endif
      </tbody>
      </table>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="../../js/app.js"></script>
    <script type="text/javascript">
        $('#formOrgUpdateModal').on('submit',function(e){
            e.preventDefault();
            console.log('meow');
            let edit_id = $('#updateOrg').val();
            console.log(edit_id);
            let name = $('#name').val();
            let ogrn = $('#ogrn').val();
            let oktmo = $('#oktmo').val();

            $.ajax({
                url: "/org/"+edit_id+"/edit",
                url:"{{route('edit-org-data',  $data->id)}}",
                type:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    name:name,
                    ogrn: ogrn,
                    oktmo:oktmo,
                },
                success: function(response){
                    var obj = JSON.parse(response);
                    console.log(obj);
                    if (typeof obj.msg_errors !== 'undefined') {
                        let errors_div = $('#updateOrgError');
                        for (key in obj.msg_errors) {
                            obj.msg_errors[key].forEach(function(elem) {
                                console.log(elem);
                                let p = document.createElement('p');
                                p.innerHTML = elem;
                                errors_div.append(p);
                            });
                        }
                        errors_div[0].style.display = "block";
                    } else {
                        location.reload();
                    }
                },
                error: function(response) {

                    // $('#nameErrorMsg').text(response.responseJSON.errors.name);
                    // $('#emailErrorMsg').text(response.responseJSON.errors.email);
                    // $('#mobileErrorMsg').text(response.responseJSON.errors.mobile);
                    // $('#messageErrorMsg').text(response.responseJSON.errors.message);
                },
            });
        });
    </script>
@endsection





