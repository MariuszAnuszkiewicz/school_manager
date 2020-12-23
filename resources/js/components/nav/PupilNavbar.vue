<template>
    <div class="navi-pupil">
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
                    <a :href="urls.events">Events</a>
                </li>
                <li class="nav">
                    <a :href="urls.messages">Messages</a>
                </li>
                <li class="nav">
                    <a :href="urls.lessonPlan">Lesson Plan</a>
                </li>
                <li class="nav">
                    <a :href="urls.myTeachers">My Teachers</a>
                </li>
                <li class="sub-menu">
                    <ul class="dropdown-submenu">
                        <a class="nav-link" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            My Grades
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li class="nav">
                               <a :href="urls.myGrades.chemistry">Chemistry</a>
                            </li>
                            <li class="nav">
                               <a :href="urls.myGrades.biology">Biology</a>
                            </li>
                            <li class="nav">
                                <a :href="urls.myGrades.phisics">Phisics</a>
                            </li>
                            <li class="nav">
                                <a :href="urls.myGrades.geography">Geography</a>
                            </li>
                            <li class="nav">
                                <a :href="urls.myGrades.mathematics">Mathematics</a>
                            </li>
                            <li class="nav">
                                <a :href="urls.myGrades.english">English</a>
                            </li>
                            <li class="nav">
                                <a :href="urls.myGrades.literature">Literature</a>
                            </li>
                            <li class="nav">
                                <a :href="urls.myGrades.history">History</a>
                            </li>
                        </ul>
                    </ul>
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
                events: '/pupil/events',
                messages: '/pupil/messages',
                lessonPlan: '/pupil/lesson-plan',
                myTeachers: '/pupil/my-teachers',
                myGrades: {
                    chemistry: '/pupil/my-grades/1',
                    biology: '/pupil/my-grades/2',
                    phisics: '/pupil/my-grades/3',
                    geography: '/pupil/my-grades/4',
                    mathematics: '/pupil/my-grades/5',
                    english: '/pupil/my-grades/6',
                    literature: '/pupil/my-grades/7',
                    history: '/pupil/my-grades/8',
                },

                },
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

    .sub-menu {
        position: relative;
        top: 5px;
        list-style-type: none;
    }

    .dropdown-submenu {
        list-style-type: none;
        width: 245px;
        display: block;
    }

    #navbarDropdownMenuLink {
        font-family: 'Oswald', sans-serif;
        color: #bebebe;
        text-align: center;
        font-size: 17px;
        display: inline-block;
        width: 85%;
    }

    #navbarDropdownMenuLink::after {
        position: relative;
        top: 3px;
        left: -45px;
    }

    .dropdown-menu {
        position: relative;
        background-color: #333333;
        height: auto;
    }

    .dropdown-submenu > .dropdown-menu {
        top: 0px;
        left: 100%;
        margin-top: -6px;
        margin-left: -1px;
    }

    .dropdown-submenu > .dropdown-menu > li.nav a:hover {
        background-color: #0C9A9A;
        color: #bebebe;
        text-decoration: none;
    }

    .dropdown-submenu:hover > .dropdown-menu {
        display: block;
    }

    .dropdown-submenu > a:after {
        display: block;
        content: " ";
        float: right;
        width: 0;
        height: 0;
        border-color: transparent;
        border-style: solid;
        border-width: 5px 0 5px 5px;
        border-left-color: #ccc;
        margin-top: 5px;
        margin-right: -10px;
    }

    .dropdown-submenu:hover > a:after {
        border-left-color: #fff;
    }

    .dropdown-submenu:hover > .dropdown-menu {
        display: block;
    }

    .dropdown-submenu > a:after {
        display: block;
        content: " ";
        float: right;
        width: 0;
        height: 0;
        border-color: transparent;
        border-style: solid;
        border-width: 5px 0 5px 5px;
        border-left-color: #ccc;
        margin-top: 5px;
        margin-right: -10px;
    }

</style>
