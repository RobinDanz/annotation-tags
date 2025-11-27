import TagsContainer from './TagsContainer.vue';
import './annotationsTabPlugins.js';

// import TagsMultiSelect from './TagsMultiSelect.vue';
// import { createApp } from 'vue';

// const PARENT_SELECTOR = '.annotations-tab-item__sub-item'
// const INJECTION_KEY = '__child_injected__'

// function startInjectionObserver() {
//   scanAndInject();

//   const observer = new MutationObserver(() => {
//     scanAndInject();
//   })

//   observer.observe(document.body, {
//     childList: true,
//     subtree: true
//   })
// }

// function inject(container) {
//     if (container[INJECTION_KEY]) return;

//     const newEl = document.createElement('span');
//     newEl.style = 'float: right; margin-right: 4px; margin-top: -4px;'
//     container.appendChild(newEl);

//     console.log(container);

//     const props = {
//         annotationId: Number(container.dataset.annotationId)
//     }

//     // app.provide()

//     const app = createApp(TagsMultiSelect, props);
//     const vm = app.mount(newEl);
//     container[INJECTION_KEY] = true;
// }

// function scanAndInject() {
//     const elements = document.querySelectorAll(PARENT_SELECTOR);
//     for(const el of elements) {
//         inject(el);
//     }
// }

// window.startInjectionObserver = startInjectionObserver;

biigle.$mount('tags-container', TagsContainer);