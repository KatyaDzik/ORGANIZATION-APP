@extends('layouts.app')

@section('doc-title')
Organizations
@endsection

<!-- Вывод информации о организации -->
@section('content')
<div class="container">
{{--    МОДАЛЬНОЕ ОКНО ДОБАВЛЕНИЯ ОРГАНИЗАЦИИ--}}
    <div style="display: flex; justify-content: space-between; margin-top: 30px;"><h1 style="margin-top: 0px;">Организации</h1>
        <button id="btnOpenModalCreateOrg" style="padding: 4px 8px" class="btn btn-success">Добавить</button></div>

    <div id="formOrgCreateModal" class="mymodal">
        <form style="margin: 20px;"  class="form_modal" action="/" method="post">
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
                <button type="button" id="btnCloseCreateModalOrg" class="btn btn-secondary">Закрыть</button>
                <button type="submit" id="createOrg" class="btn btn-primary">Сохраить</button>
            </div>
        </form>
    </div>

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
          <td><a href="{{route('delete-org-by-id',  $el->id)}}"><button class="btn btn-danger">Удалить</button></a></td>
        </tr>
      @endforeach

      </tbody>
      </table>
    <div>{{$orgs->links()}}</div>
    @endif
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="../../js/app.js"></script>
<script>
        // логика модального окна для формы обновления организации
        var modal_create_org = document.getElementById('formOrgCreateModal');
        var btn_open_create_org = document.getElementById('btnOpenModalCreateOrg');
        var btn_close_create_org = document.getElementById('btnCloseCreateModalOrg');
        btn_open_create_org.onclick = function () {
            modal_create_org.style.display = "block";
        };
        btn_close_create_org.onclick = function () {
            modal_create_org.style.display = "none";
        };
        window.onclick = function (event) {
            if (event.target == modal) {
                modal_create_org.style.display = "none";
            }
        };
    </script>
<script type="text/javascript">
    $('#formOrgCreateModal').on('submit',function(e){
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
                if (typeof obj.msg_errors !== 'undefined') {
                    let errors_div = $('#createOrgError');
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
