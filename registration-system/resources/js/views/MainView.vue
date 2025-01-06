<script setup>
import ContentTable from '../components/ContentTable.vue'
import AddNewUserField from '../components/AddNewUserField.vue'
import SearchField from '../components/SearchField.vue'
</script>

<template>
  <div class="bg-grey-700 flex justify-center flex-col rounded-lg">
    <div class="flex flex-col justify-around">
      <h2 class="text-3xl text-white">Lietotāju reģistrēšana</h2>
      <!-- <h3 class="text-xl text-white">Welcome to template page!</h3> -->
    </div>
    <div class="flex flex-row flex-wrap my-4 justify-between">
      <SearchField @errorMessage="errorMessage = $event"/>
        <p v-if="errorMessage" class="mt-4 error w-full">
        {{ errorMessage }}
      </p>
    </div>

    <AddNewUserField @submit="addNewUser($event)" />
    <ContentTable class="rounded-lg" />
  </div>
</template>

<script>
import axios from 'axios'
import { useUserStore } from '@/stores/dataStore'

export default {
  data() {
    return {
      username: 'Gunta',
      showAddNewUserField: false,
      userStore: null,
      errorMessage: ''
    }
  },
  mounted() {
    this.userStore = useUserStore()
    this.fetchAllUsers()
  },
  methods: {
    addNewUser(value = true) {
      this.showAddNewUserField = value
    },
    fetchAllUsers() {
      axios
        .get('/api/getAllUsers')
        .then((res) => {
          console.log('All users: ', res.data);
          this.userStore.setAllUsers(res.data)
        })
        .catch((error) => {
          console.error('Error at login data fetching: ' + error)
        })
    }
  }
}
</script>
