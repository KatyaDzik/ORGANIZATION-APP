@extends('layouts.app')

@section('doc-title')
Organizations
@endsection

<!-- Вывод информации о организации -->
@section('content')
<div class="container">

    <div style="display: flex; justify-content: space-between; margin-top: 30px;"><h1 style="margin-top: 0px;">Организации</h1>
        <button value="create-org-form" style="padding: 4px 8px" class="btn btn-success btn-open-modal">Добавить</button></div>
    {{--    МОДАЛЬНОЕ ОКНО ДОБАВЛЕНИЯ ОРГАНИЗАЦИИ--}}
    <x-modal-window id="create-org-form">
        <form style="margin: 20px;"  id="CreateOrg">
            {{--     Блок для вывода ошибок       --}}
            <div style="display: none" id="createOrgError" class="alert alert-danger"></div>
            {{--         Форма       --}}
            @csrf
            <div class="form-group">
                <label for="name">Название</label><span class="required-field"> *</span>
                <input type="text" class="form-control" id="name" name="name" >
            </div>

            <div class="form-group">
                <label for="ogrn">ОГРН</label><span class="required-field"> *</span>
                <input type="text" class="form-control" id="ogrn" name="ogrn">
            </div>

            <div class="form-group">
                <label for="oktmo">ОКТМО</label><span class="required-field"> *</span>
                <input type="text" class="form-control" id="oktmo" name="oktmo">
            </div>

            <div class="modal-footer">
                <button type="button" id="btnCloseCreateModalOrg" class="btn btn-secondary btn-close-modal">Закрыть</button>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </x-modal-window>

{{--    ТАБЛИЦА ВЫВОДА ВСЕХ ИМЕЮЩИХСЯ ЮЗЕРОВ--}}
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
          <td><a><button class="btn btn-danger btn-open-modal" value="{{'delete-opg-'.$el->id}}">Удалить</button></a></td>
        </tr>
        <x-modal-window id="{{'delete-opg-'.$el->id}}">
            <h2 style="text-align: center; margin: 30px 0;">Удалить организацию <br/><b>{{$el->name}}</b> ?</h2>
            <div class="modal-footer">
                <button type="button" id="btnCloseModal" class="btn btn-secondary btn-close-modal">Закрыть</button>
                <button id="btnDeleteOrg" value="{{$el->id}}"  class="btn btn-danger">Удалить</button>
            </div>
        </x-modal-window>
      @endforeach

      </tbody>
      </table>
    <div>{{$orgs->links()}}</div>
    @endif
    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script type="text/javascript">
    $('#btnDeleteOrg').on('click',function(e){
        console.log('mmm');
        let org_id = $('#btnDeleteOrg').val();
        $.ajax({
            url: "/org/delete/"+org_id,
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
    $('#CreateOrg').on('submit',function(e){
        e.preventDefault();
        let name = $('#name').val();
        let ogrn = $('#ogrn').val();
        let oktmo = $('#oktmo').val();
        $.ajax({
            url: '/create',
            type:"POST",
            data:{
                "_token": "{{ csrf_token() }}",
                name:name,
                ogrn: ogrn,
                oktmo:oktmo,
            },
            success: function(response){
                console.log(response);
                var obj = JSON.parse(response);
                console.log(obj);
                // let errors_div = document.getElementById('createOrgError');
                let errors_div = $('#createOrgError');
                if (typeof obj.msg_errors !== 'undefined') {
                    errors_div.empty();
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
@endsection
