@can('create', \Biigle\LabelTree::class)
    <a href="{{route('index-tags')}}" class="btn btn-default" title="Manage annotation tags">
        <i class="fa fa-tags"></i> Manage Tags
    </a>
@else
    <button class="btn btn-default" title="Guests are not allowed to create manage tags" disabled>
        <i class="fa fa-tags"></i> Manage Tags
    </button>
@endcan