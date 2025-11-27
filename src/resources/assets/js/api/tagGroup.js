import { Resource } from "../import";
export default Resource('api/v1/tag-groups', {}, {
    delete: {
        method: 'DELETE',
        url: 'api/v1/tag-groups/{id}',
    },
    update: {
        method: 'PUT',
        url: 'api/v1/tag-groups/{id}',
    },
});