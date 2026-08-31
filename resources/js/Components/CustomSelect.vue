<template>
  <div class="custom-select" :class="{ 'is-disabled': disabled, 'is-open': isOpen }">
    <!-- زر الاختيار -->
    <div
      class="select-trigger"
      @click="toggleDropdown"
      :class="{ 'has-value': modelValue }"
    >
      <span class="select-value">
        {{ selectedLabel || placeholder }}
      </span>
      <svg class="select-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="6 9 12 15 18 9"/>
      </svg>
    </div>

    <!-- القائمة المنسدلة -->
    <Transition name="dropdown">
      <div v-if="isOpen" class="select-dropdown">
        <div class="dropdown-list">
          <div
            v-for="option in options"
            :key="option.id"
            class="dropdown-item"
            :class="{ 'is-selected': modelValue === option.id }"
            @click="selectOption(option)"
          >
            <span>{{ option.name }}</span>
            <svg v-if="modelValue === option.id" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </div>
          <div v-if="loading" class="dropdown-loading">
            <span class="spinner-small"></span> جاري التحميل...
          </div>
          <div v-if="options.length === 0 && !loading" class="dropdown-empty">
            لا توجد جهات متاحة
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  modelValue: {
    type: [Number, String, null],
    default: null
  },
  options: {
    type: Array,
    required: true,
    default: () => []
  },
  placeholder: {
    type: String,
    default: 'اختر الجهة الحكومية'
  },
  disabled: {
    type: Boolean,
    default: false
  },
  loading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue'])

const isOpen = ref(false)

const selectedLabel = computed(() => {
  const selected = props.options.find(opt => opt.id === props.modelValue)
  return selected ? selected.name : null
})

const toggleDropdown = () => {
  if (!props.disabled) {
    isOpen.value = !isOpen.value
  }
}

const selectOption = (option) => {
  emit('update:modelValue', option.id)
  isOpen.value = false
}

// إغلاق القائمة عند النقر خارج المكون
const handleClickOutside = (event) => {
  const selectElement = event.target.closest('.custom-select')
  if (!selectElement && isOpen.value) {
    isOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
.custom-select {
  position: relative;
  width: 100%;
  user-select: none;
}

.select-trigger {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 14px;
  background: rgba(255,255,255,.06);
  border: 1px solid rgba(255,255,255,.1);
  border-radius: 10px;
  color: rgba(255,255,255,.85);
  font-size: .85rem;
  cursor: pointer;
  transition: all .2s;
}

.select-trigger:hover {
  background: rgba(255,255,255,.1);
  border-color: rgba(212,168,67,.5);
}

.select-trigger.has-value {
  color: #fff;
}

.custom-select.is-disabled .select-trigger {
  opacity: .5;
  cursor: not-allowed;
}

.select-value {
  flex: 1;
  text-align: right;
}

.select-arrow {
  transition: transform .2s;
  color: rgba(255,255,255,.4);
}

.custom-select.is-open .select-arrow {
  transform: rotate(180deg);
}

/* القائمة المنسدلة */
.select-dropdown {
  position: absolute;
  top: calc(100% + 6px);
  left: 0;
  right: 0;
  z-index: 100;
  background: #0a1a2f;
  border: 1px solid rgba(255,255,255,.15);
  border-radius: 12px;
  backdrop-filter: blur(12px);
  box-shadow: 0 12px 28px rgba(0,0,0,.4);
  overflow: hidden;
  max-height: 280px;
  overflow-y: auto;
}

.dropdown-list {
  padding: 6px 0;
}

.dropdown-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 14px;
  font-size: .85rem;
  color: rgba(255,255,255,.7);
  cursor: pointer;
  transition: background .15s;
}

.dropdown-item:hover {
  background: rgba(212,168,67,.2);
  color: #fff;
}

.dropdown-item.is-selected {
  background: rgba(212,168,67,.15);
  color: #d4a843;
  font-weight: 500;
}

.dropdown-loading, .dropdown-empty {
  padding: 20px;
  text-align: center;
  font-size: .8rem;
  color: rgba(255,255,255,.4);
}

.spinner-small {
  display: inline-block;
  width: 12px;
  height: 12px;
  border: 2px solid rgba(255,255,255,.2);
  border-top-color: #d4a843;
  border-radius: 50%;
  animation: spin .6s linear infinite;
  margin-left: 8px;
  vertical-align: middle;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* انتقال القائمة */
.dropdown-enter-active, .dropdown-leave-active {
  transition: opacity .15s, transform .15s;
}
.dropdown-enter-from, .dropdown-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
</style>
