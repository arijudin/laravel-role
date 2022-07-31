<script>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/inertia-vue3';
import JetApplicationLogo from '@/Jetstream/ApplicationLogo.vue'
import JetDialogModal from './DialogModal.vue';
import JetFormSection from '@/Jetstream/FormSection.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';
import Pagination from '@/Components/Pagination.vue';
import {
  Listbox,
  ListboxLabel,
  ListboxButton,
  ListboxOptions,
  ListboxOption,
} from '@headlessui/vue';


export default {
  name: 'Welcome',
  components: { JetApplicationLogo, JetDialogModal, JetFormSection, JetLabel, JetInput, JetInputError, JetButton, JetSecondaryButton, Pagination, Listbox, ListboxLabel, ListboxButton, ListboxOptions, ListboxOption, },
  setup() {
    const user = usePage().props.value.user
    const users = ref({})
    const q = ref('')
    const componentKey = ref(0)
    const options= ref({
      perPageItem: 50,
      perPage: [5, 10, 20, 50, 100],
      page_number: 1
    })
    const paginate = ref({})
    const editModal = ref(false)
    const expExcel = ref(false)
    const expPdf = ref(false)

    const roles = ref([ 'ADMIN', 'MODERATOR', 'USER' ])
    const selectedRole = ref(roles.value[0])
    
    const dataTable = ref(['ADMIN', 'MODERATOR', 'USER'] )
    if (user.role == 'MODERATOR') {
      roles.value = ['MODERATOR', 'USER'] 
      dataTable.value = ['MODERATOR', 'USER'] 
    } else if (user.role == 'USER') {
      roles.value = ['USER']
      dataTable.value = ['USER'] 
    }

    const form = useForm({
        _method: 'PUT',
        id: 0,
        name: '',
        email: '',
        role: '',
        biodata: '',
        processing: false,
    });

    const fetch = () => {
      axios.get(route('api.users.get'), {
        params: {
          role: dataTable.value,
          find: q.value,
          page: options.value.page_number,
          per_page: options.value.perPageItem,
        }
      }).then(response => {
        const resp = response.data
        users.value = resp
        paginate.value = resp.pagination
      });
    }

    const findRow = () => {
      fetch()
    }

    const changePageNumber = (number) => {
      options.value.page_number = number
      fetch()
    }

    const roleBadges = (role) => {
      if (role == 'ADMIN') return 'bg-slate-500'
      if (role == 'MODERATOR') return 'bg-blue-500'
      if (role == 'USER') return 'bg-green-500'
    }

    const editRow = (item) => {
      editModal.value = true
      form.id = item.id
      form.name = item.name
      form.role = item.role
      form.email = item.email
      form.biodata = item.biodata

      selectedRole.value = item.role
    }

    const updateProfileInformation = () => {
      form.role = selectedRole
      axios.put(route('api.users.update', form.id), form)
        .then(response => {
          closeModal()
          toastr.success(response.data.message);
          fetch()
        })
    }

    const deleteRow = (id) => {
      if (!confirm('Are you sure want to delete this data?')) return;
      axios.delete(route('api.users.delete', id))
        .then(response => {
          toastr.success(response.data.message);
          fetch()
        })
    }

    const closeModal = () => {
      editModal.value = false
    }

    fetch()
    return {
      user,
      users,
      roles,
      selectedRole,
      form,
      paginate,
      q,
      findRow,
      changePageNumber,
      componentKey,
      options,
      roleBadges,
      editRow,
      editModal,
      updateProfileInformation,
      deleteRow,
      closeModal,
      expExcel,
      expPdf
    }

  }
}
</script>

<template>
    <JetDialogModal :show="editModal" @close="closeModal">
        <template #title>
            Edit Data
        </template>

        <template #content>
            <form>
                <div class="sm:rounded-tl-md sm:rounded-tr-md">
                    <div class="grid grid-cols-6 gap-6">
                      <!-- Name -->
                      <div class="col-span-6">
                          <JetLabel for="name" value="Name" />
                          <JetInput
                              id="name"
                              v-model="form.name"
                              type="text"
                              class="mt-1 block w-full"
                              autocomplete="name"
                          />
                          <JetInputError :message="form.errors.name" class="mt-2" />
                      </div>

                      <!-- Email -->
                      <div class="col-span-6">
                          <JetLabel for="email" value="Email" />
                          <JetInput
                              id="email"
                              v-model="form.email"
                              type="email"
                              class="mt-1 block w-full"
                          />
                          <JetInputError :message="form.errors.email" class="mt-2" />
                      </div>

                      <!-- Role -->
                      <div class="col-span-6">
                          <JetLabel for="role" value="Role" />
                          <Listbox v-model="selectedRole">
                            <div class="relative mt-1">
                              <!-- border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full -->
                              <ListboxButton
                                class="relative w-full cursor-default rounded-md bg-white py-2 pl-3 pr-10 text-left border border-gray-300 focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300"
                              >
                                <span class="block truncate">{{ selectedRole }}</span>
                                <span
                                  class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                                >
                                  <i class='bx bx-expand-vertical text-sm text-gray-400' aria-hidden="true" ></i>
                                </span>
                              </ListboxButton>

                              <transition
                                leave-active-class="transition duration-100 ease-in"
                                leave-from-class="opacity-100"
                                leave-to-class="opacity-0"
                              >
                                <ListboxOptions
                                  class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                >
                                  <ListboxOption
                                    v-slot="{ active, selected }"
                                    v-for="role in roles"
                                    :key="role"
                                    :value="role"
                                    as="template"
                                  >
                                    <li
                                      :class="[
                                        active ? 'bg-slate-100 text-slate-900' : 'text-slate-600',
                                        'relative cursor-default select-none py-2 px-4',
                                      ]"
                                    >
                                      <span
                                        :class="[
                                          selected ? 'font-medium' : 'font-normal',
                                          'block truncate',
                                        ]"
                                        >{{ role }}</span
                                      >
                                      <span
                                        v-if="selected"
                                        class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600"
                                      >
                                      </span>
                                    </li>
                                  </ListboxOption>
                                </ListboxOptions>
                              </transition>
                            </div>
                          </Listbox>
                          <JetInputError :message="form.errors.role" class="mt-2" />
                      </div>

                      <!-- Biodata -->
                      <div class="col-span-6">
                          <JetLabel for="biodata" value="Biodata" />
                          <textarea
                              id="biodata"
                              v-model="form.biodata"
                              type="text"
                              class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"
                              autocomplete="off"
                          ></textarea>
                          <JetInputError :message="form.errors.biodata" class="mt-2" />
                      </div>
                    </div>
                </div>
            </form>
        </template>

        <template #footer>
            <JetSecondaryButton @click="closeModal">
                Cancel
            </JetSecondaryButton>

            <JetButton
                class="ml-3"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                @click="updateProfileInformation">
                Update
            </JetButton>
        </template>
    </JetDialogModal>
    <div>
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <div>
                <JetApplicationLogo class="block h-12 w-auto" />
            </div>

            <div class="mt-8 text-2xl">
                Welcome to your Jetstream application!
            </div>

            <div class="mt-6 text-gray-500">
                Laravel Jetstream provides a beautiful, robust starting point for your next Laravel application. Laravel is designed
                to help you build your application using a development environment that is simple, powerful, and enjoyable. We believe
                you should love expressing your creativity through programming, so we have spent time carefully crafting the Laravel
                ecosystem to be a breath of fresh air. We hope you love it.
            </div>
        </div>

    </div>
    <div class="bg-white bg-opacity-25 p-5">
      <div class="mb-3 flex justify-between items-center">
        <div class="relative inline-block">
          <jet-input 
            type="search"
            placeholder="Cari data user"
            @input="findRow()"
            class="block w-80 pr-9 placeholder:text-slate-400" v-model="q" />
          <div class="h-42 flex items-start absolute top-3 right-3">
            <i class='bx bx-search w-4 h-4 text-gray-500'></i>
          </div>
        </div>
        <div v-if="user.role == 'ADMIN'" class="inline-flex items-center gap-4">
          <a :href="!expPdf ? route('api.users.export_pdf') : '#'" target="_blank" :disabled="expExcel" :class="expExcel ? 'hover:cursor-not-allowed opacity-80 text-slate-500' : 'hover:shadow-md hover:cursor-pointer text-rose-400'" class="h-8 w-8 flex items-center justify-center border border-slate-100"><i class='bx bxs-file-pdf text-2xl' ></i></a>
          <a :href="!expExcel ? route('api.users.export_excel') : '#'" target="_blank" :disabled="expExcel" :class="expExcel ? 'hover:cursor-not-allowed opacity-80 text-slate-500' : 'hover:shadow-md hover:cursor-pointer text-emerald-500'" class="h-8 w-8 flex items-center justify-center border border-slate-100"><i class='bx bxs-file text-2xl'></i></a>
        </div>
      </div>
      <table class="min-w-full divide-y divide-gray-200 w-full">
        <thead class="bg-slate-200">
            <tr class="rounded-t-md">
              <th scope="col" class="border px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
              <th scope="col" class="border px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
              <th scope="col" class="border px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Biodata</th>
              <th scope="col" class="border px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Role</th>
              <th v-if="user.role != 'USER'" scope="col" class="border px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider"><span class="sr-only">Actions</span></th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-show="users.data && users.data.length < 1">
              <td :colspan="user.role != 'USER' ? 5 : 4"
                class="border px-4 py-8 whitespace-nowrap text-center text-sm font-medium align-top">
                Data tidak ditemukan.
              </td>
          </tr>
          <tr v-for="item in users.data" :key="item.id"
            class="odd:bg-slate-50 hover:bg-slate-100">
            <td class="border px-4 py-2 align-top">{{ item.name }}</td>
            <td class="border px-4 py-2 align-top">{{ item.email }}</td>
            <td class="border px-4 py-2 align-top">{{ item.biodata }}</td>
            <td class="border px-4 py-2 align-top">
              <span :class="`${roleBadges(item.role)} text-sm py-[2px] px-3 rounded text-white lowercase`">{{ item.role }}</span>
            </td>
            <td v-if="user.role != 'USER'" class="border px-4 py-2 align-top">
              <div class="flex gap-x-5">
                <a @click="editRow(item)" class="text-blue-400 hover:text-blue-500 transition-all duration-200 ease-linear hover:cursor-pointer">Edit</a>
                <a @click="deleteRow(item.id)" class="text-red-400 hover:text-red-500 transition-all duration-200 ease-linear hover:cursor-pointer">Delete</a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <pagination
        :key="componentKey"
        :options="options"
        v-on:change="changePageNumber()"
      >
        <template #nav>
          <nav v-show="paginate.last_page != 1" class="inline-flex rounded-md -space-x-px select-none" aria-label="Pagination">
              <div class="flex text-sm font-semibold">
                  <span v-if="paginate.current_page == 1"
                    class="rounded-md min-w-[36px] h-9 px-1 mx-1 bg-indigo-50 text-sm text-gray-300 flex items-center justify-center">
                    <i class='bx bx-chevron-left text-2xl' ></i>
                  </span>
                  <a v-else
                    class="rounded-md min-w-[36px] h-9 px-1 mx-1 bg-indigo-50 hover:bg-indigo-200 text-gray-400 hover:text-gray-600 flex items-center justify-center cursor-pointer"
                    @click="changePageNumber(null, paginate.prev_page_url)">
                    <i class='bx bx-chevron-left text-2xl' ></i>
                  </a>

                  <a v-if="paginate.current_page >= 1"
                    class="rounded-md min-w-[36px] h-9 px-1 mx-1 hover:bg-indigo-200 text-sm  hover:text-gray-600 flex items-center justify-center cursor-pointer"
                    :class="paginate.current_page == 1 ? 'bg-indigo-200 text-gray-600' : 'bg-indigo-50 text-gray-400'"
                    @click="changePageNumber(1)">
                    1
                  </a>

                  <span v-if="paginate.current_page > 3"
                    class="rounded-md min-w-[36px] h-9 px-1 mx-1 bg-indigo-50 text-sm text-gray-300 flex items-center justify-center">
                    ...
                  </span>
                  
                  <template v-for="(link, p) in paginate.links" :key="p">
                      <template v-if="link.label >= paginate.current_page - 1 
                        && link.label <= paginate.current_page + 1">
                          <a v-if="link.url && link.label != 1" @click="link.active ? '' : changePageNumber(link.label, link)"
                              class="rounded-md min-w-[36px] h-9 px-1 mx-1 hover:bg-indigo-200  hover:text-gray-600 flex items-center justify-center"
                              :class="link.active ? 'bg-indigo-200 text-gray-600' : 'bg-indigo-50 text-gray-400 cursor-pointer'">
                              <span v-html="link.label"></span>
                          </a>
                      </template>
                  </template>

                  <span v-if="paginate.current_page < paginate.last_page - 2"
                    class="rounded-md min-w-[36px] h-9 px-1 mx-1 bg-indigo-50 text-sm text-gray-300 flex items-center justify-center">
                    ...
                  </span>

                  <a v-if="paginate.current_page < paginate.last_page - 1"
                    class="rounded-md min-w-[36px] h-9 px-1 mx-1 bg-indigo-50 hover:bg-indigo-200 text-gray-400 hover:text-gray-600 flex items-center justify-center cursor-pointer"
                    @click="changePageNumber(paginate.last_page)">
                    {{ paginate.last_page }}
                  </a>

                  <span v-if="paginate.current_page == paginate.last_page"
                    class="rounded-md min-w-[36px] h-9 px-1 mx-1 bg-indigo-50 text-sm text-gray-300 flex items-center justify-center">
                    <i class='bx bx-chevron-right text-2xl' ></i>
                  </span>
                  <a v-else
                    class="rounded-md min-w-[36px] h-9 px-1 mx-1 bg-indigo-50 hover:bg-indigo-200 text-gray-400 hover:text-gray-600 flex items-center justify-center cursor-pointer"
                    @click="changePageNumber(null, paginate.next_page_url)">
                    <i class='bx bx-chevron-right text-2xl' ></i>
                  </a>
              </div>

              <!-- <div v-else class="flex">
                  <a @click="paginate.links[0].active ? '' : changePageNumber(1)"
                      class="relative inline-flex items-center my-2 text-lg font-medium"
                      :class="paginate.links[0].active ? 'text-gray-500' : 'text-gray-400 cursor-pointer'">
                      <i class='bx bx-chevron-left text-2xl' ></i>
                  </a>
                  <a @click="paginate.links[paginate.links.length - 1].url ? '' : changePageNumber(paginate.links.length)"
                      class="relative inline-flex items-center my-2 text-lg font-medium"
                      :class="paginate.links[paginate.links.length - 1].active ? 'text-gray-500' : 'text-gray-400 cursor-pointer'">
                      <i class='bx bx-chevron-right text-2xl' ></i>
                  </a>
              </div> -->
          </nav>
        </template>
      </pagination>
    </div>
</template>
