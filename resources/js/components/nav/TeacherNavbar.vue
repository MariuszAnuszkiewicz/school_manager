<template>
    <div class="navi-teacher">
        <div v-if="isOpenActive === true" :style="openBurger">
            <button class="burger-btn" @click="toggle()"><i class="fas fa-bars"></i></button>
        </div>
        <div v-else :style="closeBurger">
            <button class="burger-btn" @click="toggle()"><i class="fas fa-bars"></i></button>
        </div>
        <div v-if="isOpenActive === true" class="links-container">
            <button class="close-btn" @click="closeButton()"><i class="fas fa-times"></i></button>
            <ul class="navbar">
                <li class="nav">
                    <a :href="urls.listPupils">List Pupils</a>
                </li>
                <li class="nav">
                    <a :href="urls.selectedPupils">Selected Pupils</a>
                </li>
                <li class="nav">
                    <a :href="urls.myMessages">My Messages</a>
                </li>
                <li class="nav">
                    <a :href="urls.listEmails">List Emails</a>
                </li>
                <li class="nav">
                    <a :href="urls.assignRating">Assign Rating</a>
                </li>
                <li class="nav">
                    <a :href="urls.listRatings">List Ratings</a>
                </li>
                <li class="nav">
                    <a :href="urls.fillPresence">Fill Presence</a>
                </li>
                <li class="nav">
                    <a :href="urls.createEvents">Create Events</a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isOpenActive: false,
            urls: {
                listPupils: '/teacher/pupils',
                selectedPupils: '/teacher/selected-pupils',
                myMessages: '/teacher/my-messages',
                listEmails: '/teacher/list-emails',
                assignRating: '/teacher/assign-rating',
                listRatings: '/teacher/list-ratings',
                fillPresence: '/teacher/fill-presence',
                createEvents: '/teacher/events-by-calendar',
            }
        }
    },
    computed: {
        openBurger() {
            return {
                'display': 'none',
            }
        },
        closeBurger() {
            return {
                'display': 'block',
            }
        }
    },
    methods: {
        toggle() {
            this.isOpenActive = !this.isOpenActive
        },
        closeButton() {
            this.isOpenActive = false;
        },
    },
}
</script>

<style scoped>

    .burger-btn {
        position: relative;
        left: 2%;
        display: block;
        padding: 10px;
        font-size: 18px;
        background-color: #9c9891;
        border: none;
        outline: none;
        cursor: pointer;
    }
    .links-container {
        position: relative;
        top: 5px;
        width: 295px;
        height: auto;
        background-color: #343A40;
        z-index: 99;
        float: left;
        padding-bottom: 25px;
    }
    .close-btn {
        margin: 5px 5px 5px 5px;
    }
    li.nav {
        font-family: 'Oswald', sans-serif;
        list-style-type: none;
        margin: 7px;
        font-size: 17px;
    }
    a {
        padding: 2px;
        font-family: 'Oswald', sans-serif;
    }
    li.nav a {
        display: block;
        width: 245px;
        text-align: center;
        color: #bebebe;
    }
    li.nav a:hover {
        background-color: #333333;
        color: #bebebe;
        text-decoration: none;
    }

</style>
