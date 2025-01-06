<script setup>
import RecordRow from "../components/RecordRow.vue";
</script>
<template>
    <div class="p-0.5 text-white justify-center items-center rounded-lg">
        <div
            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-15 p-3 w-full h-full bg-gray-200 rounded-md text-black font-bold text-base sticky top-0 z-10 items-center"
        >
            <div class="col-span-2 flex flex-row gap-2">
                <h2>Vārds Uzvārds</h2>
                <button @click="sortByName()">
                    <img class="h-6 w-5" src="../assets/sort-icon-md.png" />
                </button>
            </div>
            <div class="col-span-1">
                <h2>Telefons</h2>
            </div>
            <div class="col-span-3 ml-12">
                <h2>E-pasts</h2>
            </div>
            <div class="col-span-2 flex flex-row gap-2">
                <h2>Abonaments</h2>
            </div>
            <div class="col-span-2 flex flex-row gap-2">
                <div>Sākuma datums</div>
            </div>
            <div class="col-span-2 flex flex-row gap-2">
                <div>Beigu datums</div>
            </div>
            <div class="col-span-1 flex flex-row gap-2">
                <h2>Status</h2>
            </div>
            <div class="col-span-2 flex flex-row gap-2">
                <h2>Rediģēt</h2>
            </div>
        </div>

        <div v-if="allUsers" v-for="(user, index) in allUsers" :key="index">
            <!-- Show single user record -->
            <RecordRow
                :id="user.id || 0"
                :name="user.first_name + ' ' + (user.last_name || '')"
                :phoneNumber="user.phone_number"
                :email="user.email"
                :type="user.subscription_type"
                :startDate="user.start_date"
                :endDate="user.end_date"
                isActive="Aktīvs"
                :daysTillEndOfSub="user.days_till_end_of_sub"
                :hideArrow="false"
                :editMode="false"
                @userHistoryOpened="openUserHistory"
                @editNewUserOpenIds="editNewUserOpenIds"
            />
        </div>
        <div v-else class="flex mt-10 justify-center">
            <h2>Lietotāji nav reģistrēti!</h2>
        </div>
    </div>
</template>

<script>
import { useUserStore } from "@/stores/dataStore";
import { mapState } from "pinia";

export default {
    data() {
        return {
            subscriptionMenuOpen: false,
            statusMenuOpen: false,
            subriptionTypes: [
                "RX",
                "FF",
                "1 nedēļa",
                "Teens",
                "10x abonaments",
            ],
            checkedItems: ["RX", "FF", "1 nedēļa", "Teens", "10x abonaments"],
            statusTypes: ["Aktīvs", "Neaktīvs"],
            checkedStatusItems: ["Aktīvs", "Neaktīvs"],
            userStore: {},
            sortFirstNameAscending: false,
            sortEndDateAscending: true,
            userIdAddNewUserOpened: [],
            userIdHistoryOpened: [],
            userHistoryItems: {},
            allUsers: [],
        };
    },
    watch: {
        searchedUsers: {
            handler(newValue) {
                if (newValue.length) {
                    this.allUsers = [];
                    this.$nextTick(() => (this.allUsers = this.searchedUsers));
                } else {
                    this.allUsers = [];
                    this.$nextTick(() => (this.allUsers = this.users));
                }
            },
            deep: true,
        },
        users: {
            handler() {
                this.allUsers = this.users;
            },
            immediate: true,
            deep: true,
        },
    },
    computed: {
        ...mapState(useUserStore, ["users", "searchedUsers"]),
    },
    mounted() {
        this.userStore = useUserStore();
    },
    methods: {
        editNewUserOpenIds(id, isOpened) {
            if (isOpened && !this.userIdAddNewUserOpened.includes(id)) {
                this.userIdAddNewUserOpened.push(id);
            } else {
                let index = this.userIdAddNewUserOpened.indexOf(id);
                if (index !== -1) {
                    this.userIdAddNewUserOpened.splice(index, 1);
                }
            }
        },
        openUserHistory(firstName, lastName, id) {
            if (
                this.userIdHistoryOpened.includes(id) &&
                Object.keys(this.userHistoryItems).includes(id)
            ) {
                delete this.userHistoryItems[id];
                this.userIdHistoryOpened = this.userIdHistoryOpened.filter(
                    (userId) => userId !== id
                );
                return;
            }
            this.userIdHistoryOpened.push(id);
            const newItem = {
                ...this.userHistoryItems,
                [id]: this.userStore.getUserHistory(firstName, lastName, id),
            };
            this.userHistoryItems = newItem;
        },
        toggleFirstNameSort() {
            this.sortFirstNameAscending = !this.sortFirstNameAscending;
        },
        toggleEndDateSort() {
            this.sortEndDateAscending = !this.sortEndDateAscending;
        },
        sortByName() {
            this.toggleFirstNameSort();
            let allUsers = this.allUsers;
            this.userStore.setAllUsers([]);
            this.$nextTick(() =>
                this.userStore.sortUsersByFirstName(
                    allUsers,
                    this.sortFirstNameAscending
                )
            );
        },
    },
};
</script>
<style>
#dropDownMenu {
    left: 39.5%;
}
#statusDropDownMenu {
    right: 16%;
    z-index: 1;
}
p {
    font-size: 0.75rem;
}
</style>
