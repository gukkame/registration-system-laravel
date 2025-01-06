import { defineStore } from "pinia";

export const useUserStore = defineStore("userStore", {
    state: () => ({
        users: [],
        searchedUsers: [],
    }),
    getters: {
        allUsers: (state) => state.users,
    },
    actions: {
        setAllUsers(users) {
            this.users = users;
        },
        setSearchedUsers(users) {
            this.searchedUsers = users;
        },
        addNewUser(user) {
            this.users.push(user);
        },
        sortUsersByFirstName(allUsers, ascending = true) {
            const sortedUsers = allUsers.sort((a, b) => {
                const nameA = a.first_name.toLowerCase();
                const nameB = b.first_name.toLowerCase();
                if (nameA < nameB) return ascending ? -1 : 1;
                if (nameA > nameB) return ascending ? 1 : -1;
                return 0;
            });

            this.setAllUsers(sortedUsers);
        },
        editUserById(id, newUserData) {
            const userIndex = this.users.findIndex((user) => user.id === id);

            if (userIndex !== -1) {
                this.users[userIndex] = {
                    ...this.users[userIndex],
                    ...newUserData,
                };
            } else {
                console.warn(`User with id ${id} not found.`);
            }
        },
    },
});
