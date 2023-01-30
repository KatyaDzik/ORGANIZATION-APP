@extends('layouts.app')

@section('doc-title')
    {{$user->last_name}} {{$user->first_name}} {{$user->middle_name}}
@endsection

<!-- Вывод информации о пользователе -->
@section('content')
   <div class="container">
       <div style="display: flex; padding: 20px 20px 20px 0;">
           <h1 style="margin: 0; margin-right: 20px;">{{$user->last_name}} {{$user->first_name}} {{$user->middle_name}} </h1>
           <button class="btn-icon btn-open-modal" value="update-user"><img width="20px" src="{{ URL::asset('img/pen.png') }}" alt=""></button>
           <button class="btn-icon btn-open-modal" style="margin-left: 20px" value="delete-user"><a><img width="20px" src="{{ URL::asset('img/trash.png') }}" alt=""></a></button>
       </div>

       <x-modal-window id="update-user">
           <form id="UpdateUser" style="margin: 20px;" >
               <div style="display: none" id="updateUserError" class="alert alert-danger"></div>
               @csrf
               <div class="form-group">
                   <label for="lastname">Фамилия</label><span class="required-field"> *</span>
                   <input type="text" class="form-control" value="{{$user->last_name}}" id="lastname" name="lastname"  placeholder="Фамилия">
               </div>

               <div class="form-group">
                   <label for="firstname">Имя</label><span class="required-field"> *</span>
                   <input type="text" class="form-control" value="{{ $user->first_name }}" id="firstname" name="firstname" placeholder="Имя">
               </div>

               <div class="form-group">
                   <label for="middlename">Отчество</label><span class="required-field"> *</span>
                   <input type="text" class="form-control" id="middlename" name="middlename" value="{{ $user->middle_name }}" placeholder="Отчество">
               </div>

               <div class="form-group">
                   <label for="birthday">Дата Рождения</label>
                   <input type="date" class="form-control" id="birthday" name="birthday" value="{{ $user->birthday}}" placeholder="Дата рождения">
               </div>

               <div class="form-group">
                   <label for="inn">ИНН</label><span class="required-field"> *</span>
                   <input type="number" class="form-control" id="inn" name="inn" value="{{ $user->inn }}" placeholder="ИНН">
               </div>

               <div class="form-group">
                   <label for="snils">СНИЛС</label><span class="required-field"> *</span>
                   <input type="number" class="form-control" id="snils" name="snils" value="{{ $user->snils }}" placeholder="СНИЛС">
               </div>

               <div class="modal-footer">
                   <button type="button" id="btnCloseModal" class="btn btn-secondary btn-close-modal">Закрыть</button>
                   <button type="submit" id="" class="btn btn-primary">Обновить</button>
               </div>
           </form>
       </x-modal-window>
       <x-modal-window id="delete-user">
           <h2 style="text-align: center; margin: 30px 0;">Удалить пользователя <br/><b>{{$user->last_name}} {{$user->first_name}} {{$user->middle_name}}</b> ?</h2>
           <div class="modal-footer">
               <button type="button" id="btnCloseModal" class="btn btn-secondary btn-close-modal">Закрыть</button>
               <button type="submit" id="DeleteUser"  class="btn btn-danger">Удалить</button>
           </div>
       </x-modal-window>
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
                           <td><a href="{{route('org-data-by-id',  $el->id)}}"><button class="btn btn-warning">Просмотр</button></a>
                           <td><a><button value="{{'delete-user-from-org-'.$el->id}}" class="btn btn-danger btn-open-modal">Удалить</button></a></td>

                       </tr>

                       <x-modal-window id="{{'delete-user-from-org-'.$el->id}}">
                           <h2 style="text-align: center; margin: 30px 0;">Удалить пользователя <br/><b>{{$user->last_name}} {{$user->first_name}} {{$user->middle_name}}</b> <br/> из организации <i>{{$el->name}}</i></h2>
                           <div class="modal-footer">
                               <button type="button" id="btnCloseModal" class="btn btn-secondary btn-close-modal">Закрыть</button>
                               <button type="submit" id="deleteUserfromOrg" value="{{$el->id}}"  class="btn btn-danger">Удалить</button>
                           </div>
                       </x-modal-window>
                   @endforeach
               </tbody>
           </table>
       @endif
   </div>

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script type="text/javascript">
            $('#UpdateUser').on('submit',function(e){
            e.preventDefault();
            let firstname = $('#firstname').val();
            let lastname = $('#lastname').val();
            let middlemame = $('#middlename').val();
            let birthday = $('#birthday').val();
            let inn = $('#inn').val();
            let snils = $('#snils').val();

            $.ajax({
            url:"{{route('update-user',  $user->id)}}",
            type:"PUT",
            data:{
            "_token": "{{ csrf_token() }}",
            first_name: firstname,
            last_name: lastname,
            middle_name: middlemame,
            birthday: birthday,
            inn: inn,
            snils: snils
        },
            success: function(response){
                var obj = JSON.parse(response);
                console.log(obj);
                if (typeof obj.msg_errors !== 'undefined') {
                let errors_div = $('#updateUserError');
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
            alert(response);
             },
         });
        });
    </script>

   <script type="text/javascript">
       $('#DeleteUser').on('click',function(e){
           e.preventDefault();
           $.ajax({
               url: "{{route('delete-user', $user->id)}}",
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

               },
           });
       });
   </script>

   <script type="text/javascript">
       $('#deleteUserfromOrg').on('click',function(e){
           let org_id = $('#deleteUserfromOrg').val();
           $.ajax({
               url: '/org/'+org_id+'/delete/user/'+{{$user->id}},
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
@endsection


