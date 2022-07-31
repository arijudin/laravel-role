<script>
    import { ref } from 'vue'
    import {
      Listbox,
      ListboxLabel,
      ListboxButton,
      ListboxOptions,
      ListboxOption,
    } from '@headlessui/vue'
    
    export default {
        props: {
          data: {
            type: Object,
            default: []
          },
          filters: {
            type: Object,
            default: []
          },
          options: {
            type: Object,
            default: {}
          },
          style: {
            type: Object,
            default: []
          },
        },
        emits: ['update:filters', 'update:options'],
        components: {
            Listbox,
            ListboxLabel,
            ListboxButton,
            ListboxOptions,
            ListboxOption
        },
        data() {
          return {
            selected: ref(this.options.perPageItem)
          }
        },
        mounted() {
        },
        computed: {
          getPerPage() {
            let uri = window.location.search.substring(1)
            let params = new URLSearchParams(uri)
            let perPage = params.get('per_page')

            return perPage
          }
        },
        methods: {
            serialize(obj, prefix) {
                var str = [],
                    p;
                for (p in obj) {
                    if (obj.hasOwnProperty(p)) {
                    var k = prefix ? prefix + "[]" : p,
                        v = obj[p];

                        if (v != '') {
                            str.push((v !== null && typeof v === "object") ?
                                this.serialize(v, k) :
                                k + "=" + encodeURIComponent(v));
                        }
                    }
                }
                return str.join("&");
            },
            selectItemPerPage(val) {
              this.options.perPageItem = val
            }
        },
    }
</script>

<template>
    <div
        class="flex items-center"
        :class="style != 'plain' ? 'mt-4 justify-between' : 'justify-end'">
        <div class="text-gray-500 text-sm flex items-center mr-4">
            <slot></slot>

            <!-- <input type="text" v-model="options.perPageItem" ref="test" v-on:change="(e) => selectItemPerPage(e)"> -->
            <Listbox as="div" v-model="selected" class="hidden">
                <ListboxButton
                  class="inline-flex items-center justify-between relative w-20 h-[42px] bg-white group cursor-default rounded p-2 text-left border border-gray-200 focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                >
                  <span class="block truncate">{{ options.perPageItem }}</span>
                  <i class='bx bx-chevron-down h-4 w-4 text-gray-600 stroke-1 group-hover:-rotate-180 transition-all duration-300 ease-linear' ></i>
                </ListboxButton>

                <transition
                  leave-active-class="transition duration-100 ease-in"
                  leave-from-class="opacity-100"
                  leave-to-class="opacity-0"
                >
                  <ListboxOptions
                    class="mb-1 w-16 overflow-auto rounded-md bg-white py-1 text-base shadow-lg border-gray-200"
                  >
                    <ListboxOption
                      v-for="row in options.perPage"
                      v-slot="{ active, selected }"
                      :key="row"
                      :value="row"
                    >
                      <li
                        :class="[
                          active ? 'bg-blue-100 text-blue-600' : 'text-gray-700',
                          'relative cursor-default select-none py-2 px-4',
                        ]"
                      >
                        <span
                          :class="[
                            selected ? 'font-medium' : 'font-normal',
                            'block truncate',
                          ]"
                          >{{ row }}</span
                        >
                          <!-- @click="selectItemPerPage(row)" -->
                        <span
                          v-if="selected"
                          class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600"
                        >
                        </span>
                      </li>
                    </ListboxOption>
                  </ListboxOptions>
                </transition>
            </Listbox>

            <select v-if="options"
                class="placeholder-gray-300 border-gray-200 focus:ring focus:ring-indigo-100 focus:ring-opacity-50 rounded w-auto mr-3 select-none"
                v-model="options.perPageItem">
                <option v-for="row in options.perPage" :key="row" :value="row">{{row}}</option>
            </select>
            <div class="ml-3 text-sm select-none">
              <div v-if="data.total >= 1">
                  Show <span class="font-semibold text-gray-700">{{ data.current_page == 1 ? "1" : (data.per_page * (data.current_page - 1)) + 1 }}</span> - <span class="font-semibold text-gray-700">{{ data.last_page == data.current_page ? data.total : data.per_page * data.current_page }}</span> of <span class="font-semibold text-gray-700">{{ data.total }}</span> data
              </div>
              <div v-else>
                  Show <span class="font-semibold text-gray-700">0</span> data
              </div>
            </div>
        </div>
        <slot name="nav"></slot>
    </div>
</template>