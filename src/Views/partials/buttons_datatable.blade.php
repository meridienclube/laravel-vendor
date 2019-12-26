<div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
    <a href="{{ route($nameRoute . '.show', $obj->id) }}" type="button" class="btn btn-primary">
        <i class="material-icons ">visibility</i>
    </a>
    <a href="{{ route($nameRoute . '.edit', $obj->id) }}" type="button" class="btn btn-info">
        <i class="material-icons">edit</i>
    </a>
    <a href="javascript:void(0);" type="button" class="btn btn-danger"
       onclick="event.preventDefault();
           if(!confirm('Tem certeza que deseja deletar este item?')){ return false; }
           document.getElementById('destroy-{{ $obj->id }}').submit();">
        <i class="material-icons">delete</i>
    </a>
</div>
{{ Form::open(['method'  => 'DELETE', 'route' => [$nameRoute . '.destroy', $obj->id], 'id' => 'destroy-' . $obj->id]) }}
{{ Form::hidden('id', $obj->id) }}
{{ Form::close() }}

