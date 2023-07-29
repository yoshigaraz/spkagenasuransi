<option value="" selected="selected">--Pilih Alternatif--</option>
@foreach($alternative as $a)
    <option value="{{$a->id}}">{{$a->description}}</option>
@endforeach