/**
 * Resource for tag requests.
 * 
 * @type {Vue.resource}
 */
export default Vue.resource('api/v1/tags', {}, {
    delete: {
        method: 'DELETE',
        url: 'api/v1/tags/{id}',
    },
    update: {
        method: 'PUT',
        url: 'api/v1/tags/{id}',
    },
    getAnnotation: {
        method: 'GET',
        url: 'api/v1/tags/annotation/{id}',
    },
    attachTag: {
        method: 'POST',
        url: 'api/v1/tags/annotation/{id}',
    },
    detachTag: {
        method: 'DELETE',
        url: 'api/v1/tags/annotation/{id}',
    },
    updateTag: {
        method: 'PUT',
        url: 'api/v1/tags/annotation/{id}'
    },
    importTags: {
        method: 'POST',
        url: 'api/v1/tags/import',
    }
});
