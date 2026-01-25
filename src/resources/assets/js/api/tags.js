import { Resource } from "../import";
export default Resource('api/v1/tags', {}, {
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
        url: 'api/v1/tags/annotations/{id}',
    },
    updateRelation: {
        method: 'POST',
        url: 'api/v1/tags/annotations/{id}',
    },
    importTags: {
        method: 'POST',
        url: 'api/v1/tags/import',
    }
});
