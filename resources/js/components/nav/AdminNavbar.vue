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
                   <a :href="urls.assignSubjectTeacher">Assign Subject</a>
                </li>
                <li class="nav">
                   <a :href="urls.searchUser">Search User</a>
                </li>
                <li class="sub-menu">
                    <ul class="dropdown-submenu">
                        <a class="nav-link" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Create Lesson Plan
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li class="nav">
                               <a :href="urls.createLessonPlanClassA">Lesson Plan Class A</a>
                            </li>
                            <li class="nav">
                               <a :href="urls.createLessonPlanClassB">Lesson Plan Class B</a>
                            </li>
                            <li class="nav">
                               <a :href="urls.createLessonPlanClassC">Lesson Plan Class C</a>
                            </li>
                            <li class="nav">
                               <a :href="urls.createLessonPlanClassD">Lesson Plan Class D</a>
                            </li>
                            <li class="nav">
                               <a :href="urls.createLessonPlanClassE">Lesson Plan Class E</a>
                            </li>
                            <li class="nav">
                               <a :href="urls.createLessonPlanClassF">Lesson Plan Class F</a>
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
               assignSubjectTeacher: '/admin/assign-subject-teacher',
               searchUser: '/admin/search-user',
               createLessonPlanClassA: '/admin/create-lesson-plan/1',
               createLessonPlanClassB: '/admin/create-lesson-plan/2',
               createLessonPlanClassC: '/admin/create-lesson-plan/3',
               createLessonPlanClassD: '/admin/create-lesson-plan/4',
               createLessonPlanClassE: '/admin/create-lesson-plan/5',
               createLessonPlanClassF: '/admin/create-lesson-plan/6',
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
        left: -15px;
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
