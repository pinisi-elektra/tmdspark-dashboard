@push('bottom')

    @if (App::getLocale() != 'en')
        <script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/datepicker/locales/bootstrap-datepicker.'.App::getLocale().'.js') }}"
                charset="UTF-8"></script>
    @endif
    <script type="text/javascript">
        var lang = '{{App::getLocale()}}';
        $(function () {
            @if($form['subDate'])
            var subDate = new Date();
            subDate.setDate(subDate.getDate()-{{ $form['subDate'] }});
            @endif

            $('.input_date').datepicker({
                format: 'yyyy-mm-dd',
                @if($form['subDate'])
                startDate: subDate,
                @endif
                @if (in_array(App::getLocale(), ['ar', 'fa']))
                rtl: true,
                @endif
                language: lang
            });

            $('.open-datetimepicker').click(function () {
                $(this).next('.input_date').datepicker('show');
            });

        });

    </script>
@endpush
