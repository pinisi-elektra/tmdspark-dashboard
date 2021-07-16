
@if(Request::input('fileformat') == 'pdf')
    <h3>{{Request::input('filename')}}</h3>
@endif

{{-- <table> --}}
<table border="1" width='100%' cellpadding='3' cellspacing="0" style='border-collapse: collapse;font-size:12px'>
    <thead>
        <tr>
            @php
            foreach ($columns as $col) {
                if (Request::get('columns')) {
                    if (! in_array($col['name'], Request::get('columns'))) {
                        continue;
                    }
                }
                $colname = !isset($raw) ? $col['label'] : $col['name'];
                echo "<th>$colname</th>";
            }
            @endphp
        </tr>
    </thead>

    @if(!isset($raw))
        <tbody>
        @if(count($result) == 0)
            <tr>
                <td colspan='{{count($columns)+1}}' align="center">No Data Avaliable</td>
            </tr>
        @else
            @foreach($result as $row)
                <tr>
                    <?php
                    foreach ($columns as $col) {
                        if (Request::get('columns')) {
                            if (! in_array($col['name'], Request::get('columns'))) {
                                continue;
                            }
                        }

                        $value = @$row->{$col['field']};
                        $value = @str_replace("&", "&amp;", $row->{$col['field']});

                        $title = @$row->{$title_field};

                        if (@$col['image']) {
                            if ($value == '') {
                                $value = asset('assets/images/no-image.jpg');
                            }
                            $pic = (strpos($value, 'http://') !== FALSE) ? $value : asset($value);
                            $pic_small = $pic;
                            if (Request::input('fileformat') == 'pdf') {
                                echo "<td><a data-lightbox='roadtrip' rel='group_{{$table}}' title='$col[label]: $title' href='".$pic."'><img class='img-circle' width='40px' height='40px' src='".$pic_small."'/></a></td>";
                            } else {
                                echo "<td>$pic</td>";
                            }
                        } elseif (@$col['download']) {
                            $url = (strpos($value, 'http://') !== FALSE) ? $value : asset($value);
                            echo "<td><a class='btn btn-sm btn-primary' href='$url' target='_blank' title='Download File'>Download</a></td>";
                        } else {

                            //limit character
                            if ($col['str_limit']) {
                                $value = trim(strip_tags($value));
                                $value = str_limit($value, $col['str_limit']);
                            }

                            if ($col['nl2br']) {
                                $value = nl2br($value);
                            }

                            if (Request::input('fileformat') == 'pdf') {
                                if (! empty($col['callback_php'])) {

                                    foreach ($row as $k => $v) {
                                        $col['callback_php'] = str_replace("[".$k."]", $v, $col['callback_php']);
                                    }
                                    @eval("\$value = ".$col['callback_php'].";");
                                }

                                //New method for callback
                                if (isset($col['callback'])) {
                                    $value = call_user_func($col['callback'], $row);
                                }
                            }
                            echo "<td>".$value."</td>";
                        }
                    }
                    ?>
                </tr>
            @endforeach
        @endif
        </tbody>
    @endif
</table>
