import sys
import json

if __name__ == '__main__':
    f = open(sys.argv[1], 'r')
    js = json.load(f)
    f.close()

    coco_data = js['coco']
    tags = js['tags']

    tags_flat = {}

    for tag in tags:
        for annotation in tag.get('annotations', []):
            image_annotation = annotation.get('image_annotation_id')

            if image_annotation not in tags_flat:
                tags_flat[image_annotation] = []

            tags_flat[image_annotation].append(tag['name'])
    
    annotations_data = coco_data.get('annotations', [])

    for annotation in annotations_data:
        if annotation['id'] in tags_flat:
            annotation['tags'] = tags_flat[annotation['id']]

    print(json.dumps(coco_data))





