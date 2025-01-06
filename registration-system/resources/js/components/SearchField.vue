<template>
  <div>
    <input
      type="text"
      v-model="givenName"
      placeholder="Meklēt pēc uzvārda"
      class="bg-transparent bg-gray-800 text-white p-3 outline-none bottom-border border-b border-gray-200"
      @input="searchUser"
    />
    <button
      type="button"
      class="relative inline-flex items-center justify-center p-0.5 ml-4 overflow-hidden text-sm font-medium text-white rounded-lg group bg-gradient-to-br from-custom-grey to-red-500 group-hover:from-cyan-500 group-hover:to-blue-500 hover:text-black"
      @click="cancel()"
    >
      <span
        class="w-full relative px-3 py-2 transition-all ease-in duration-75 bg-gray-900 rounded-md group-hover:bg-opacity-0"
      >
        Atcelt
      </span>
    </button>
  </div>
</template>

<script>
import axios from 'axios'
import { useUserStore } from '@/stores/dataStore'
import { mapState } from 'pinia'

export default {
  data() {
    return {
      givenName: ''
    }
  },
  emits: ['errorMessage'],
  mounted() {
    this.userStore = useUserStore()
  },
  computed: {
    ...mapState(useUserStore, ['filteredUsers']),
  },
  methods: {
    removeDiacritics(str) {
      return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
    },
    searchUser() {
      if (this.givenName === '') {
        this.userStore.setSearchedUsers([])
        return
      }

      const normalizedGivenName = this.removeDiacritics(this.givenName.toLowerCase())
      const filteredArray = this.filteredUsers.filter(
        (obj) =>
          this.removeDiacritics(obj.LastName.toLowerCase()).includes(normalizedGivenName) ||
          this.removeDiacritics(obj.FirstName.toLowerCase()).includes(normalizedGivenName)
      )

      if (filteredArray.length) {
        this.$emit('errorMessage', '')
        this.userStore.setSearchedUsers(filteredArray)
        return
      }
      this.$emit('errorMessage', 'Lietotājs netika atrasts')
      this.userStore.setSearchedUsers([])
      return
      axios
        .post(import.meta.env.VITE_SERVER_URL + '/searchUser', { givenName: this.givenName })
        .then((response) => {
          console.log(response.data)
          // this.userStore.setSearchedUsers(response.data || [])
          //Add user to searchedUsers Array, when pagination is implemented
        })
        .catch((error) => {
          console.error(error)
        })
    },
    cancel() {
      this.givenName = ''
      this.$emit('errorMessage', '')
      this.userStore.setSearchedUsers([])
    }
  }
}
</script>
