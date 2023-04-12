<script setup>
import CourseCard from '@/Components/CourseCard.vue';
import GeneralLayout from '@/Layouts/GeneralLayout.vue';
import { Link } from '@inertiajs/vue3';


defineProps( {
    title: String,
    courses: Object
} );
let brands = [ 'dangote', 'amaiden', 'fmn', 'justrite', 'mikano', 'chevron' ];
let categories = [
    { name: 'Machine operation', active: true, slug: 'machine', title: 'Get skilled in heavy machine operation', description: "The globally skilled labor shortage is one of the greatest challenges facing the construction industry.With the rise in the construction of commercial mega structures like Lekki deep Seaport, Dangote refinery, and EKo –Atlantic projects, the skill, and competency –gap for heavy machine operators became wider hence the increase in demand for the heavy machine operator in the labour market. Our machinery operator training is both classroom training and hands-on-the-job learning under the supervision of experienced instructors.", category: "Machine Operation" },
    { name: 'Digital Entrepreneurial', active: false, slug: 'digital', title: 'Expand your career opportunities with digital entrepreneurial skills', description: "Combining hands-on learning with real work experience, the 8-week intensive training offers young people the opportunity to acquire sought- after skills to launch careers as Digital Marketing Managers, Graphic Design specialists, or Digital Entrepreneurs.", category: "Digital Entrepreneurship" },
    { name: "SME's Digital Boot-camp", active: false, slug: 'bootcamp', title: 'Grow your business skills with Digital Business Bootcamp', description: "These digital boot camp sessions will help small-scale business owners, storefront operators, and self- employed to scale up their businesses with a virtual store via digital and social media channels.These channels will allow them to connect with clientele on the, go, thereby increasing patronage and income.", category: "SME Digital Boot-camp" },
]
</script>

<template>
    <GeneralLayout :title="title">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <img src="" class="rounded mr-2" alt="">
                <strong class="mr-auto">Bootstrap</strong>
                <small class="text-muted">just now</small>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                See? Just like this.
            </div>
        </div>
        <template #banner>
            <div class="banner" style="background-image: url('/assets/img/slide3.jpg');">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <div id="carouselId" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner" role="listbox">
                                <div class="carousel-item active">
                                    <img src='/assets/img/slide1.jpg' class="w-100 d-block slider-images" alt="First slide">
                                </div>
                                <div class="carousel-item">
                                    <img src='/assets/img/slide2.jpg' class="w-100 d-block slider-images "
                                        alt="Second slide">
                                </div>
                                <div class="carousel-item">
                                    <img src='/assets/img/slide4.jpg' class="w-100 d-block slider-images "
                                        alt="Second slide">
                                </div>
                                <div class="carousel-item">
                                    <img src='/assets/img/slide5.jpg' class="w-100 d-block slider-images "
                                        alt="Second slide">
                                </div>
                                <div class="carousel-item">
                                    <img src='/assets/img/slide6.jpg' class="w-100 d-block slider-images "
                                        alt="Second slide">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselId"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselId"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-5 mt-5 d-flex flex-column justify-content-center card-body" style="min-height:60vh">
                        <h1 class="display-3 fw-bold text-white mb-4">Bridging the Gap Initiative</h1>
                        <p class="lead text-white">Bridge the gap program is a skill-to-wealth project designed
                            to equip and elevate unemployed young men and women above the poverty line through
                            industry-relevant digital, technical, and vocational skills for self-development,
                            sustenance, and wealth creation.</p>
                    </div>
                </div>
            </div>
        </template>

        <h1 class="fw-bold mb-2 text-dark">A growing selection of courses</h1>
        <div class="col-lg-8">
            <p class="lead">Choose from about 5+ hands-on, practical oriented courses. Complete the training with good
                grades
                and get deployed to top companies.
            </p>
        </div>
        <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
            <li class="nav-item" role="presentation" v-for="(tab, index) in categories" :key="index">
                <button class="nav-link text-dark font-size-16 text-uppercase ps-0" :class="{ 'active': tab.active }"
                    data-bs-toggle="tab" :data-bs-target="`#${tab.slug}`" type="button" role="tab">{{ tab.name }}</button>
            </li>
        </ul>

        <div class="tab-content border border-secondary  card card-body rounded-0 shadow-none">
            <div class="tab-pane" :class="{ 'active': tab.active }" :id="tab.slug" role="tabpanel"
                v-for="(tab, index) in categories" :key="index">
                <div class="col-lg-9">
                    <h4>{{ tab.title }}</h4>
                    <p class="my-4">{{ tab.description }}</p>
                </div>
                <div class="row">
                    <div class="col-lg-3" v-for="course in courses.filter(e => e.program == tab.category)" :key="course.id">
                        <CourseCard :course="course"></CourseCard>
                    </div>
                </div>
            </div>
        </div>
        <section class="my-5">
            <h3 class="text-center text-dark">Trusted and partnered by top brands</h3>
            <p class="lead text-center mb-5">We train the top leading companies employees and outsource our bright gradutes
            </p>
            <div class="row g-5 mb-5">
                <div class="col-md-2 col-6" v-for="(brand, index) in brands" :key="index">
                    <img :src="`/assets/img/${brand}.jpg`" class="logo" alt="">
                </div>
            </div>
        </section>
        <section class="my-5">
            <div class="text-center my-5 banner p-5" style="background-image: url('/assets/img/headers-2.jpg');">
                <p class="display-6 fw-bold text-white mb-5">Additionally, the program makes provision for individuals and
                    organizations who
                    are looking to sponsor wards or staff</p>
                <button class="btn border-white rounded-0 text-uppercase btn-lg text-white">Learn more on the
                    sponsorship program.</button>
            </div>
        </section>


    </GeneralLayout>
</template>

<style scoped>
.banner {
    background-color: #080807ef;
    background-repeat: repeat;
    background-size: contain;
    background-blend-mode: overlay;
    background-position: center;
}

.slider-images {
    width: 100%;
    height: 80vh;
    object-fit: cover;
}

.logo {
    width: 100%;
    object-fit: cover;
}

button.active {
    font-weight: bold
}
</style>