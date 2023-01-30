
<div class="mymodal" {{$attributes}}>
        <div style="margin: 20px;"  class="modal-content" method="post">
            <button class="btn-icon btn-close-modal" style="padding: 10px; position: relative; z-index: 1; left: 95%;"><img width="20px" src="{{ URL::asset('img/close.png') }}" alt=""></button>
            <hr/>
                {{$slot}}
        </div>
</div>

{{--чтобы активировать окно, нужно предать в value кнопки такое же значение как и в атрибут id модального окна,--}}
{{--также в классе кнопки указать btn-open-modal--}}

{{--<button class="btn-open-modal" value="add-user">Добавить</button>--}}
{{--<x-modal-window id="add-user">--}}
{{--    <h1>Далее помещается контент</h1>--}}
{{--</x-modal-window>--}}

{{--Чтобы добавить кнопку закрытия , добавьте к кнопке класс btn-close-modal--}}
