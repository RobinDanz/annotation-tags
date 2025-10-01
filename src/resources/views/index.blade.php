@push('scripts')
    <script src="{{ cachebust_asset('vendor/annotation-tags/scripts/main.js') }}"></script>
    <script>
        biigle.$declare('tagsDisplay.tags', {!! $tags !!});
    </script>

@endpush

@extends('app')
@section('title', 'Tags')
@section('content')
    <div class="container" id="tags-container">
        <h2>Tags</h2>
        <div class="row">
            <div class="col-xs-6">
                <div class="panel panel-default">
                    <ul class="label-tree__list">
                        <tag-item :key="tag.id" v-for="(tag, index) in tags" :tag="tag" v-on:save="saveTag"
                            v-on:delete="deleteTag"></tag-item>
                    </ul>
                </div>
            </div>
            <div class="col-xs-6">
                <tabs v-cloak>
                    <tab title="New Tag">
                        <tag-form v-on:submit="createTag"></tag-form>
                    </tab>
                    <tab title="Report">
                        <tag-report></tag-report>
                    </tab>
                </tabs>
            </div>
        </div>

    </div>

@endsection