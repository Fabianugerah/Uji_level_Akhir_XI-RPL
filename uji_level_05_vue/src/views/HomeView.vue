<script setup>
import { ref, onMounted, reactive } from 'vue'
import api from '../services/api'

const items = ref([])
const isSaved = ref(false)
const fetchLoading = ref(false)

// Form data untuk TAMBAH buku
const formData = reactive({
  name: '',
  description: '',
  author: '',
})
// State untuk error validasi form TAMBAH
const formErrors = reactive({
  name: '',
  description: '',
  author: '',
})

// ----- State untuk EDIT -----
const showEditModal = ref(false)
const editingItem = reactive({
  // Data buku yang sedang diedit
  id: null,
  name: '',
  description: '',
  author: '',
})
const isEditing = ref(false) // Loading state untuk submit edit
// State untuk error validasi form EDIT
const editFormErrors = reactive({
  name: '',
  description: '',
  author: '',
})

// MENGAMBIL DATA DARI API (GET)
const fetchItems = async () => {
  try {
    fetchLoading.value = true
    const response = await api.get('/books')
    items.value = response.data.data || response.data
    console.log('Fetched items:', items.value)
  } catch (error) {
    console.error('Error fetching items:', error)
  } finally {
    fetchLoading.value = false
  }
}

// ----- VALIDASI FORM -----
const validateForm = (data, errors) => {
  let isValid = true
  // Reset errors
  errors.name = ''
  errors.description = ''
  errors.author = ''

  if (!data.name.trim()) {
    errors.name = 'Name book is required.'
    isValid = false
  }
  if (!data.description.trim()) {
    errors.description = 'Description is required.'
    isValid = false
  }
  if (!data.author.trim()) {
    errors.author = 'Author is required.'
    isValid = false
  }
  return isValid
}

// MENGIRIM DATA KE API (POST) - Tambah Buku
const submitForm = async () => {
  if (!validateForm(formData, formErrors)) {
    return // Hentikan jika validasi gagal
  }
  try {
    isSaved.value = true
    const response = await api.post('/books', formData)
    console.log('Add response:', response)

    formData.name = ''
    formData.description = ''
    formData.author = ''
    // Reset errors setelah sukses
    formErrors.name = ''
    formErrors.description = ''
    formErrors.author = ''

    fetchItems()
  } catch (error) {
    console.error('Error submitting form:', error)
  } finally {
    isSaved.value = false
  }
}

// ----- FUNGSI UNTUK EDIT -----
const openEditModal = (item) => {
  Object.assign(editingItem, item)
  editFormErrors.name = ''
  editFormErrors.description = ''
  editFormErrors.author = ''
  showEditModal.value = true
}

const closeEditModal = () => {
  showEditModal.value = false
  editingItem.id = null
  editingItem.name = ''
  editingItem.description = ''
  editingItem.author = ''
}

const submitEditForm = async () => {
  if (!validateForm(editingItem, editFormErrors)) {
    return
  }
  if (!editingItem.id) {
    console.error('No item ID to edit.')
    return
  }
  try {
    isEditing.value = true
    const payload = {
      name: editingItem.name,
      description: editingItem.description,
      author: editingItem.author,
    }
    const response = await api.put(`/books/${editingItem.id}`, payload)
    console.log('Edit response:', response)
    fetchItems()
    closeEditModal()
  } catch (error) {
    console.error('Error updating item:', error)
  } finally {
    isEditing.value = false
  }
}

// ----- State untuk DELETE -----
const showDeleteModal = ref(false)
const itemToDelete = ref(null)
const isDeleting = ref(false)

// ----- FUNGSI UNTUK DELETE -----
const openDeleteModal = (item) => {
  itemToDelete.value = item
  showDeleteModal.value = true
}

const closeDeleteModal = () => {
  showDeleteModal.value = false
  itemToDelete.value = null
}

const confirmDelete = async () => {
  if (!itemToDelete.value || !itemToDelete.value.id) {
    console.error('No item to delete.')
    return
  }
  try {
    isDeleting.value = true
    const response = await api.delete(`/books/${itemToDelete.value.id}`)
    console.log('Delete response:', response)
    fetchItems()
    closeDeleteModal()
  } catch (error) {
    console.error('Error deleting item:', error)
  } finally {
    isDeleting.value = false
  }
}

onMounted(() => {
  fetchItems()
})
</script>

<template>
  <div class="space-y-4 dark:bg-gray-900 dark:text-white py-8 md:py-12 lg:py-15">
    <h2 class="font-bold text-3xl leading-[1.1] sm:text-4xl md:text-5xl text-center">
      Add Book Data
    </h2>
    <hr class="w-30 h-0.5 mx-auto my-4 bg-blue-500 border-0 rounded-full mb-7" />
    <div>
      <form class="max-w-sm mx-auto" @submit.prevent="submitForm" novalidate>
        <div class="mb-5">
          <label for="name" class="block mb-2 text-sm font-medium text-white text-left"
            >Name Book</label
          >
          <input
            type="text"
            id="name"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            :class="{ 'border-red-500 dark:border-red-500': formErrors.name }"
            placeholder="Enter name book..."
            v-model="formData.name"
          />
          <p v-if="formErrors.name" class="mt-1 text-xs text-red-500 dark:text-red-400">
            {{ formErrors.name }}
          </p>
        </div>
        <div class="mb-5">
          <label for="description" class="block mb-2 text-sm font-medium text-white text-left"
            >Description</label
          >
          <input
            type="text"
            id="description"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            :class="{ 'border-red-500 dark:border-red-500': formErrors.description }"
            placeholder="Enter description book..."
            v-model="formData.description"
          />
          <p v-if="formErrors.description" class="mt-1 text-xs text-red-500 dark:text-red-400">
            {{ formErrors.description }}
          </p>
        </div>
        <div class="mb-5">
          <label for="author" class="block mb-2 text-sm font-medium text-white text-left"
            >Author</label
          >
          <input
            type="text"
            id="author"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            :class="{ 'border-red-500 dark:border-red-500': formErrors.author }"
            placeholder="Enter author book..."
            v-model="formData.author"
          />
          <p v-if="formErrors.author" class="mt-1 text-xs text-red-500 dark:text-red-400">
            {{ formErrors.author }}
          </p>
        </div>

        <button
          type="submit"
          class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
          :disabled="isSaved"
        >
          <span v-if="isSaved">Saving Data...</span>
          <span v-else>Save</span>
        </button>
      </form>
    </div>

    <div class="px-4">
      <h2
        class="py-4 text-2xl sm:text-2xl md:text-3xl font-bold leading-none tracking-tight text-left"
      >
        New Book List
      </h2>
      <hr class="w-16 h-0.5 bg-white opacity-50 border-0 rounded-full mb-5" />

      <form class="max-w-sm mb-3">
        <label for="countries" class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
          >Select Your Book</label
        >
        <select
          id="countries"
          class="bg-gray-50 border-0 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        >
          <option>Fantasy</option>
          <option>Romance</option>
          <option>Adventure</option>
          <option>Horror</option>
          <option>History</option>
          <option>Travel</option>
        </select>
      </form>

      <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-center rtl:text-right text-gray-500 dark:text-gray-400">
          <thead
            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
          >
            <tr>
              <th scope="col" class="px-6 py-3">ID BOOK</th>
              <th scope="col" class="px-6 py-3">NAME BOOK</th>
              <th scope="col" class="px-6 py-3">DESCRIPTION</th>
              <th scope="col" class="px-6 py-3">AUTHOR</th>
              <th scope="col" class="px-6 py-3">ACTIONS</th>
            </tr>
          </thead>
          <tbody v-if="fetchLoading">
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
              <td
                colspan="5"
                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center"
              >
                Processing Data...
              </td>
            </tr>
          </tbody>
          <tbody v-else-if="items && items.length > 0">
            <tr
              class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
              v-for="item in items"
              :key="item.id"
            >
              <td
                scope="row"
                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
              >
                {{ item.id }}
              </td>
              <td class="px-6 py-4">{{ item.name }}</td>
              <td class="px-6 py-4">{{ item.description }}</td>
              <td class="px-6 py-4">{{ item.author }}</td>
              <td class="px-6 py-4 space-x-2 whitespace-nowrap">
                <button
                  @click="openEditModal(item)"
                  class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                >
                  <svg
                    class="w-6 h-6 text-blue-600 dark:text-blue-500"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke="currentColor"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"
                    />
                  </svg>
                </button>
                <button
                  @click="openDeleteModal(item)"
                  class="font-medium text-red-600 dark:text-red-500 hover:underline"
                >
                  <svg
                    class="w-6 h-6 text-red-600 dark:text-red-500"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke="currentColor"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"
                    />
                  </svg>
                </button>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
              <td
                colspan="5"
                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center"
              >
                No books found.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div
      v-if="showEditModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-md">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Edit Book</h3>
        <form @submit.prevent="submitEditForm" novalidate>
          <div class="mb-4">
            <label
              for="edit-name"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >Name Book</label
            >
            <input
              type="text"
              id="edit-name"
              v-model="editingItem.name"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
              :class="{ 'border-red-500 dark:border-red-500': editFormErrors.name }"
            />
            <p v-if="editFormErrors.name" class="mt-1 text-xs text-red-500 dark:text-red-400">
              {{ editFormErrors.name }}
            </p>
          </div>
          <div class="mb-4">
            <label
              for="edit-description"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >Description</label
            >
            <input
              type="text"
              id="edit-description"
              v-model="editingItem.description"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
              :class="{ 'border-red-500 dark:border-red-500': editFormErrors.description }"
            />
            <p
              v-if="editFormErrors.description"
              class="mt-1 text-xs text-red-500 dark:text-red-400"
            >
              {{ editFormErrors.description }}
            </p>
          </div>
          <div class="mb-6">
            <label
              for="edit-author"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >Author</label
            >
            <input
              type="text"
              id="edit-author"
              v-model="editingItem.author"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
              :class="{ 'border-red-500 dark:border-red-500': editFormErrors.author }"
            />
            <p v-if="editFormErrors.author" class="mt-1 text-xs text-red-500 dark:text-red-400">
              {{ editFormErrors.author }}
            </p>
          </div>
          <div class="flex justify-end space-x-3">
            <button
              type="button"
              @click="closeEditModal"
              class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="isEditing"
              class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
            >
              <span v-if="isEditing">Saving...</span>
              <span v-else>Save Changes</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <div
      v-if="showDeleteModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-md">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
          Confirm Delete Book
        </h3>
        <p class="mb-6 text-gray-600 dark:text-gray-300">
          Are you sure you want to delete the book "<strong>{{ itemToDelete?.name }}</strong
          >"?
        </p>
        <div class="flex justify-end space-x-3">
          <button
            type="button"
            @click="closeDeleteModal"
            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
          >
            Cancel
          </button>
          <button
            @click="confirmDelete"
            :disabled="isDeleting"
            class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-500 dark:hover:bg-red-600 focus:outline-none dark:focus:ring-red-800"
          >
            <span v-if="isDeleting">Deleting...</span>
            <span v-else>Delete</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
