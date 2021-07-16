<select id='list-icon' class="form-control" name="icon" style="font-family: 'FontAwesome', Helvetica;">
    <option value="">** Select an Icon</option>
    @foreach($fontawesome as $font)
        <option value='{{$font}}' {{ ($row->icon == "$font")?"selected":"" }} data-label='{{$font}}'>{{$font}}</option>
    @endforeach
</select>
