import TagsMultiSelect from './TagsMultiSelect.vue';
import TagsContainer from './TagsContainer.vue';
import Vue from 'vue'

const PARENT_SELECTOR = '.annotations-tab-item__sub-item'
const INJECTION_KEY = '__child_injected__'

function injectIntoParent(parentInstance) {
  if (parentInstance[INJECTION_KEY]) return

  const ChildClass = Vue.extend(TagsMultiSelect)
  const childInstance = new ChildClass({
    parent: parentInstance,
    propsData: { }
  })

  childInstance.$mount()
  parentInstance.$el.appendChild(childInstance.$el)

  parentInstance[INJECTION_KEY] = childInstance
}

function scanAndInject() {
  const elements = document.querySelectorAll(PARENT_SELECTOR)
  for (const el of elements) {
    const parentInstance = el.__vue__
    if (parentInstance) {
      injectIntoParent(parentInstance)
    }
  }
}

function startInjectionObserver() {
  scanAndInject()

  const observer = new MutationObserver(() => {
    scanAndInject()
  })

  observer.observe(document.body, {
    childList: true,
    subtree: true
  })
}

startInjectionObserver();

biigle.$mount('tags-container', TagsContainer);