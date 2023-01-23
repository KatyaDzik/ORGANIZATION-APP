@extends('layouts.app')

@section('doc-title')
    {{$user->last_name}} {{$user->first_name}} {{$user->middle_name}}
@endsection

<!-- Вывод информации о пользователе -->
@section('content')
   <div class="container">
       <div style="display: flex; padding: 20px 20px 20px 0;">
           <h1 style="margin: 0; margin-right: 20px;">{{$user->last_name}} {{$user->first_name}} {{$user->middle_name}} </h1>
           <button class="pen" id="btnOpenModal"><img width="20px" src="{{ URL::asset('img/pen.png') }}" alt=""></button>
           <button class="pen" style="margin-left: 20px" id="btnDeleteUser"><a href="{{route('delete-org-by-id',  $user->id)}}"><img width="20px" src="{{ URL::asset('img/trash.png') }}" alt=""></a></button>
       </div>

       <div id="myModal" class="mymodal">
           <form style="margin: 20px;"  class="form_modal" action="/org/{{$user->id}}/adduser" method="post">
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
                   <button type="button" id="btnCloseModal" class="btn btn-secondary">Закрыть</button>
                   <button type="submit" id="" class="btn btn-primary">Обновить</button>
               </div>
           </form>
       </div>

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

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
   <script src="../../js/app.js"></script>
    <script>
        let modal = document.getElementById('myModal');
        let btnOpen = document.getElementById('btnOpenModal');
        let btnClose = document.getElementById('btnCloseModal');

        btnOpen.onclick = function () {
            modal.style.display = "block";
        }

        btnClose.onclick = function () {
            modal.style.display = "none";
        }

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
        <script type="text/javascript">
            $('#myModal').on('submit',function(e){
            e.preventDefault();
            console.log('meow');
            let first_name = $('#firstname').val();
            let last_name = $('#lastname').val();
            let middle_mame = $('#middlename').val();
            let birthday = $('#birthday').val();
            let inn = $('#inn').val();
            let snils = $('#snils').val();

            $.ajax({
            url:"{{route('update-user',  $user->id)}}",
            type:"PUT",
            data:{
            "_token": "{{ csrf_token() }}",
            first_name: first_name,
            last_name: last_name,
            middle_name: middle_mame,
            birthday: birthday,
            inn: inn,
            snils: snils
        },
            success: function(response){
                var obj = JSON.parse(response);
                console.log(obj);
                if (typeof obj.msg_errors !== 'undefined') {
                let errors_div = $('#updateUserError');
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
            alert(response);
             },
         });
        });
    </script>
    <script type="text/javascript">
        $('#btnDeleteUser').on('click',function(e){
            e.preventDefault();
            $.ajax({
                url: {{route('delete-user', $user->id)}},
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
                    alert("hi"+response);
                    console.log(response);
                },
            });
        });
    </script>
@endsection


