@push('scripts')
    <!-- <script src="{{ cachebust_asset('vendor/annotation-tags/scripts/main.js') }}"></script> -->
    {{vite_hot(base_path('vendor/annotation-tags/hot'), ['src/resources/assets/js/main.js'], 'vendor/annotation-tags')}}
    <script type="module">
        biigle.$declare('tagsDisplay.tags', {!! $tags !!});
        console.log('hello');
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
                    <tab title="Import">
                        <tag-import v-on:refresh="refresh"></tag-import>
                    </tab>
                </tabs>
            </div>
        </div>

    </div>

@endsection