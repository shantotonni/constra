import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from '../views/auth/Login.vue'
import Main from '../components/layouts/Main'
import Dashboard from '../views/dashboard/Index.vue'
import UserList from '../views/users/Index'
import MenuList from '../views/menu/List'
import MenuPermission from '../views/users/MenuPermission'
import SliderList from '../views/slider/Index'
import GalleryList from '../views/gallery/Index'
import CustomerList from '../views/customer/Index'
import WebMenu from '../views/web_menu/Index'
import SubMenu from '../views/sub_menu/Index'
import PageList from '../views/pages/Index'
import PageDetails from '../views/pages/Details.vue'
import Blog from '../views/blog/Index.vue'
import About from '../views/about/Index.vue'
import BlogDetails from '../views/blog/Details.vue'

import Testimonial from '../views/testimonial/Index.vue'
import OurTeamList from '../views/our_team/Index.vue'
import OurTeamDetails from '../views/our_team/Details.vue'
import QuestionAnswerList from '../views/question_answer/Index.vue'
import QuestionAnswerDetails from '../views/question_answer/Details.vue'

import ServiceList from '../views/services/Index.vue'
import ServiceDetails from '../views/services/Details.vue'
import WhyChooseUs from '../views/why_choose_us/Index.vue'
import WhyChooseUsDetails from '../views/why_choose_us/Details'
import CollegeList from '../views/colleges/Index.vue'

import TimeScheduleList from '../views/time_schedule/Index.vue'

import Contact from '../views/contact/Index.vue'
import CountriesList from '../views/countries/Index.vue'

import ChangePassword from '../views/settings/ChangePassword'
import Setting from '../views/settings/Setting'

import NotFound from '../views/404/Index';
import {baseurl} from '../base_url'

Vue.use(VueRouter);

const config = () => {
    let token = localStorage.getItem('token');
    return {
        headers: {Authorization: `Bearer ${token}`}
    };
}
const checkToken = (to, from, next) => {
    let token = localStorage.getItem('token');
    if (token === 'undefined' || token === null || token === '') {
        next(baseurl + 'login');
    } else {
        next();
    }
};

const activeToken = (to, from, next) => {
    let token = localStorage.getItem('token');
    if (token === 'undefined' || token === null || token === '') {
        next();
    } else {
        next(baseurl);
    }
};

const routes = [
    {
        path: baseurl,
        component: Main,
        redirect: {name: 'Dashboard'},
        children: [
            //DASHBAORD
            {path: baseurl + 'dashboard', name: 'Dashboard', component: Dashboard},

            {path: baseurl + 'user-list', name: 'UserList', component: UserList},
            {path: baseurl + 'menu-list', name: 'MenuList', component: MenuList},
            {path: baseurl + 'user-menu-permission', name: 'UserMenuPermission', component: MenuPermission},
            {path: baseurl + 'slider-list', name: 'SliderList', component: SliderList},
            {path: baseurl + 'gallery-list', name: 'GalleryList', component: GalleryList},
            {path: baseurl + 'customer-list', name: 'CustomerList', component: CustomerList},
            {path: baseurl + 'web-sub-menu', name: 'SubMenu', component: SubMenu},
            {path: baseurl + 'web-menu', name: 'WebMenu', component: WebMenu},
            {path: baseurl + 'news', name: 'Blog', component: Blog},
            {path: baseurl + 'about', name: 'About', component: About},
            {path: baseurl + 'news-details/:id', name: 'BlogDetails', component: BlogDetails},
            {path: baseurl + 'page-list', name: 'PageList', component: PageList},
            {path: baseurl + 'page-details/:id', name: 'PageDetails', component: PageDetails},
            {path: baseurl + 'testimonial', name: 'Testimonial', component: Testimonial},

            {path: baseurl + 'setting', name: 'Setting', component: Setting},


            //services
            {path: baseurl + 'service-list', name: 'ServiceList', component: ServiceList},
            {path: baseurl + 'service-details/:id', name: 'ServiceDetails', component: ServiceDetails},

            //our team
            {path: baseurl + 'our-team-list', name: 'OurTeamList', component: OurTeamList},
            {path: baseurl + 'our-team-details/:id', name: 'OurTeamDetails', component: OurTeamDetails},
            //services
            {path: baseurl + 'question-answer-list', name: 'QuestionAnswerList', component: QuestionAnswerList},
            {path: baseurl + 'question-answer-details/:id', name: 'QuestionAnswerDetails', component: QuestionAnswerDetails},

            //time schedule
            {path: baseurl + 'time-schedule', name: 'TimeScheduleList', component: TimeScheduleList},

            //why choose us
            {path: baseurl + 'why-choose-us', name: 'WhyChooseUs', component: WhyChooseUs},
            {path: baseurl + 'why-choose-us-details/:id', name: 'WhyChooseUsDetails', component: WhyChooseUsDetails},
            //colleges
            {path: baseurl + 'college-list', name: 'CollegeList', component: CollegeList},
            {path: baseurl + 'country-list', name: 'CountriesList', component: CountriesList},

            //new info module route
            {path: baseurl + 'contact-list', name: 'Contact', component: Contact},
            {
                path: baseurl + 'change-password', name: 'ChangePassword', component: ChangePassword
            },
        ],
        beforeEnter(to, from, next) {
            checkToken(to, from, next);
        }
    },
    {
        path: baseurl + 'login',
        name: 'Login',
        component: Login,
        beforeEnter(to, from, next) {
            activeToken(to, from, next);
        }
    },
    {
        path: baseurl + '*',
        name: 'NotFound',
        component: NotFound,
    },
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.baseurl,
    routes
});

router.afterEach(() => {
    $('#preloader').hide();
});

export default router
