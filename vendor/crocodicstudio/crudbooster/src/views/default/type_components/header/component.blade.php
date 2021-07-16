<div id='header{{$index}}'
    @if(isset($form['collapsed']))
    data-collapsed="{{ ($form['collapsed']===false)?'false':'true' }}"
    @endif
    class='header-title form-divider'
>

    <h4>
        <strong><i class='{{$form['icon']?:"fa fa-check-square-o"}}'></i> {{$form['label']}}</strong>
        <span class='pull-right icon'><i class='fa fa-minus-square-o'></i></span>
    </h4>
</div>
