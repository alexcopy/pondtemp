<form  action="{{ $url or Request::url() }}" method="POST">
    {{ method_field('EDIT') }}
    {{ csrf_field() }}
    <button type='submit' class="{{ $class or 'btn btn-xs btn-warning list-inline' }}" value="{{ $value or 'edit' }}">{!! $text or 'edit' !!}</button>
</form>