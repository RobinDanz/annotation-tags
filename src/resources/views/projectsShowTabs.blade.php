@push('scripts')
   <!-- <script src="{{ cachebust_asset('vendor/annotation-tags/scripts/main.js') }}"></script> -->
    {{vite_hot(base_path('vendor/annotation-tags/hot'), ['src/resources/assets/js/main.js'], 'vendor/annotation-tags')}}
@endpush