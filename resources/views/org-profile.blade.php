@extends('layouts.app')

@section('doc-title')
{{$data->name}}
@endsection

        {{-- ОСНОВНАЯ ИНФОРМАЦИЯ О ОРГАНИЗАЦИИ --}}
    @section('content')
    <div class="container">
        <div style="display: flex; padding: 20px 20px 20px 0;">
            <h1 style="margin: 0; margin-right: 20px;">{{$data->name}}</h1>
            <button class="btn-icon btn-open-modal" value="{{'update-org-'.$data->id}}"><img width="20px" src="{{ URL::asset('img/pen.png') }}" alt=""></button>
            <button class="btn-icon btn-open-modal" value="{{'delete-'.$data->id}}" style="margin-left: 20px" ><a><img width="20px" src="{{ URL::asset('img/trash.png') }}" alt=""></a></button>
        </div>
    <h4>ОГРН: {{$data->ogrn}}</h4>
    <h4>ОКТМО: {{$data->oktmo}}</h4>

        {{--   Модальное окно при удалении организации     --}}
        <x-modal-window id="{{'delete-'.$data->id}}">
            <h2 style="text-align: center; margin: 30px 0;">Удалить организацию <br/><b>{{$data->name}}</b> ?</h2>
            <div class="modal-footer">
                <button type="button" id="btnCloseModal" class="btn btn-secondary btn-close-modal">Закрыть</button>
                <button type="submit" id="btnDeleteOrg"  class="btn btn-danger">Удалить</button>
            </div>
        </x-modal-window>

        {{-- МОДАЛЬНОЕ ОКНО ДЛЯ ИЗМЕНЕНИЯ ДАННЫХ ОБ ОБГАНИЗАЦИИ --}}
        <x-modal-window id="{{'update-org-'.$data->id}}">
            <form id="OrgUpdate" style="margin: 20px;"  >
{{--                     Блок для вывода ошибок--}}
                <div style="display: none" id="updateOrgError" class="alert alert-danger"></div>
{{--                         Форма--}}
                @csrf
                <div class="form-group">
                    <label for="name">Название</label><span class="required-field"> *</span>
                    <input type="text" class="form-control" value="{{ $data->name }}" id="name" name="name" >
                </div>

                <div class="form-group">
                    <label for="ogrn">ОГРН</label><span class="required-field"> *</span>
                    <input type="text" class="form-control" value="{{ $data->ogrn }}" id="ogrn" name="ogrn">
                </div>

                <div class="form-group">
                    <label for="oktmo">ОКТМО</label><span class="required-field"> *</span>
                    <input type="text" class="form-control" id="oktmo" name="oktmo" value="{{ $data->oktmo }}">
                </div>

                <div class="modal-footer">
                    <button type="button" id="btnCloseUpdateModalOrg" class="btn btn-secondary btn-close-modal">Закрыть</button>
                    <button type="submit" id="updateOrg" value="{{$data->id}}" class="btn btn-primary">Обновить</button>
                </div>
            </form>
        </x-modal-window>

        {{-- ВЫВОД ВСЕХ ПОЛЬЗОВАТЕЛЕЙ, КОТОРЫЕ ЕСТЬ В ОРГАНИЗАЦИИ В ВИДЕ ТАБЛИЦЫ --}}
        @if (!empty($users))
            <div style="display: flex;   justify-content: space-between; padding-top: 100px;">
                <h2 style="margin-top: 10px">Пользователи</h2>
                <button class="btn btn-success btn-open-modal" value="create-user">Добавить</button></div>
            <!-- модальное окно с формой добавления -->
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
          <td>{{$el->last_name}} {{$el->first_name}} {{$el->middle_name}}</td>
          <td>{{$el->birthday}}</td>
          <td>{{$el->inn}}</td>
          <td>{{$el->snils}}</td>
            <td><a href="{{route('user-data-by-id',  $el->id)}}"><button class="btn btn-warning">Просмотр</button></a></td>
            <td><a><button value="{{'delete-user-from-org-'.$el->id}}" class="btn btn-danger btn-open-modal">Удалить</button></a></td>
        </tr>

        <x-modal-window id="{{'delete-user-from-org-'.$el->id}}">
            <h2 style="text-align: center; margin: 30px 0;">Удалить пользователя <br/><b>{{$el->last_name}} {{$el->first_name}} {{$el->middle_name}}</b> <br/> из организации <i>{{$data->name}}</i></h2>
            <div class="modal-footer">
                <button type="button" id="btnCloseModal" class="btn btn-secondary btn-close-modal">Закрыть</button>
                <button type="submit" id="deleteUserfromOrg" value="{{$el->id}}"  class="btn btn-danger">Удалить</button>
            </div>
        </x-modal-window>
      @endforeach


    @endif
      </tbody>
      </table>
        {{-- МОДАЛЬНОЕ ОКНО С ФОРМОЙ ДОБАВЛЕНИЯ НОВОГО ПОЛЬЗОВАТЕЛЯ --}}
            <x-modal-window id="create-user">

                <div style="display: none" id="createUserErrors" class="alert alert-danger"></div>

                <form id="CreateUser" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="lastname">Фамилия</label><span class="required-field"> *</span>
                        <input type="text" class="form-control"  id="lastname" name="lastname"  placeholder="Фамилия">
                    </div>

                    <div class="form-group">
                        <label for="firstname">Имя</label><span class="required-field"> *</span>
                        <input type="text" class="form-control"  id="firstname" name="firstname" placeholder="Имя">
                    </div>

                    <div class="form-group">
                        <label for="middlename">Отчество</label><span class="required-field"> *</span>
                        <input type="text" class="form-control" id="middlename" name="middlename"  placeholder="Отчество">
                    </div>

                    <div class="form-group">
                        <label for="birthday">Дата Рождения</label>
                        <input type="date" class="form-control" id="birthday" name="birthday"  placeholder="Дата рождения">
                    </div>

                    <div class="form-group">
                        <label for="inn">ИНН</label><span class="required-field"> *</span>
                        <input type="number" class="form-control" id="inn" name="inn"  placeholder="ИНН">
                    </div>

                    <div class="form-group">
                        <label for="snils">СНИЛС</label><span class="required-field"> *</span>
                        <input type="number" class="form-control" id="snils" name="snils" placeholder="СНИЛС">
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="btnCloseModal" class="btn btn-secondary btn-close-modal">Закрыть</button>
                        <button type="submit" id="btnCreateUser"  class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </x-modal-window>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script type="text/javascript">
        $('#OrgUpdate').on('submit',function(e){
            e.preventDefault();
            let edit_id = $('#updateOrg').val();
            let name = $('#name').val();
            let ogrn = $('#ogrn').val();
            let oktmo = $('#oktmo').val();

            $.ajax({
                url:"{{route('edit-org-data',  $data->id)}}",
                type:"PUT",
                data:{
                    "_token": "{{ csrf_token() }}",
                    name:name,
                    ogrn: ogrn,
                    oktmo:oktmo,
                },
                success: function(response){
                    var obj = JSON.parse(response);
                    if (typeof obj.msg_errors !== 'undefined') {
                        let errors_div = $('#updateOrgError');
                        errors_div.empty();
                        for (key in obj.msg_errors) {
                            obj.msg_errors[key].forEach(function(elem) {
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
                error: function() {
                    alert('There was some error performing the AJAX call!');
                },
            });
        });
    </script>

    <script type="text/javascript">
        $('#btnDeleteOrg').on('click',function(e){
            e.preventDefault();
            $.ajax({
                url: "{{route('delete-org-by-id',  $data->id)}}",
                type:"DELETE",
                data:{
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response){
                    if(response = "success"){
                        location.href = "{{route('organizations')}}"
                    }
                },
                error: function(response) {
                    alert('There was some error performing the AJAX call!');
                },
            });
        });
    </script>


    <script type="text/javascript">
        $('#deleteUserfromOrg').on('click',function(e){
             let user_id = $('#deleteUserfromOrg').val();
            $.ajax({
                url: '/org/'+{{$data->id}}+'/delete/user/'+user_id,
                type:"DELETE",
                data:{
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response){
                    if(response = "success"){
                        location.reload();
                    }
                },
                error: function(response) {
                    alert('There was some error performing the AJAX call!');
                },
            });
        });
    </script>


    <script type="text/javascript">
        $('#CreateUser').on('submit',function(e){
        e.preventDefault();
        let firstname = $('#firstname').val();
        let lastname = $('#lastname').val();
        let middlename = $('#middlename').val();
        let birthday = $('#birthday').val();
        let inn = $('#inn').val();
        let snils = $('#snils').val();

        $.ajax({
            url: "{{route('create-user', $data->id)}}",
            type:"POST",
            data:{
                "_token": "{{ csrf_token() }}",
                firstname: firstname,
                lastname: lastname,
                middlename: middlename,
                birthday: birthday,
                inn: inn,
                snils: snils
            },
            success: function(response){
                var obj = JSON.parse(response);
                if (typeof obj.msg_errors !== 'undefined') {
                    let errors_div = $('#createUserErrors');
                    errors_div.empty();
                    for (key in obj.msg_errors) {
                        obj.msg_errors[key].forEach(function(elem) {
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
                alert('There was some error performing the AJAX call!');
            },
        });
        });
    </script>

@endsection





