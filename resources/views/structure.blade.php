@extends('Ragnarok::template')

@section('content')

<div class="container">
    <h3>Skeleton Package</h3>
    <hr/>
    <h4>Structure</h4>

    @foreach($structure['dirs'] as $dir)
        <ul class="list-group col-sm-4">
            <li href="#" class="list-group-item active">{{$dir['parent']}}</li>
            @foreach($dir['child'] as $dir)
                <ul>
                    <li class="list-group-item active">{{$dir['name']}}</li>
                    @if(isset($dir['child']))
                        <ul>
                        @foreach($dir['child'] as $dir)
                            <li class="list-group-item">{{$dir['name']}}</li>
                        @endforeach
                        </ul>
                    @endif
                </ul>
            @endforeach
        </ul>
    @endforeach
</div>

@endsection


