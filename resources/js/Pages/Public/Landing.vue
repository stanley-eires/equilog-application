<script setup>
import CourseCard from '@/Components/CourseCard.vue';
import GeneralLayout from '@/Layouts/GeneralLayout.vue';
import { Link } from '@inertiajs/vue3';


defineProps( {
    title: String,
    courses: Object
} );

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
                <div class="">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <div id="carouselId" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner" role="listbox">
                                    <div class="carousel-item active">
                                        <img src='/assets/img/slide1.jpg' class="w-100 d-block slider-images"
                                            alt="First slide">
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
                        <div class="col-md-5 mt-5 d-flex flex-column justify-content-center card-body"
                            style="min-height:60vh">
                            <h1 class="display-3 fw-bold text-white mb-4">Bridging the Gap Initiative</h1>
                            <p class="lead text-white">Bridge the gap program is a skill-to-wealth project designed
                                to equip and elevate unemployed young men and women above the poverty line through
                                industry-relevant digital, technical, and vocational skills for self-development,
                                sustenance, and wealth creation.</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <section>
            <div class="mb-5">
                <div class="row gx-md-5 align-items-center">
                    <div class="col-lg-6">
                        <h3 class="text-dark display-6 ">Digital Entrepreneurial Training</h3>
                        <p class="lead">Combining hands-on learning with real work experience, the 8-week intensive training
                            offers young people the opportunity to acquire sought-after skills to launch careers as Digital
                            Marketing Managers, Graphic Design specialists, or Digital Entrepreneurs.</p>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">

                            <div class="col-md-6"
                                v-for="course in courses.filter(e => e.program === 'Digital Entrepreneurship')"
                                :key="course.id">
                                <CourseCard :course="course"></CourseCard>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-5">
                <div class="row gx-md-5 align-items-center">
                    <div class="col-lg-6 order-2 order-lg-1">
                        <div class="row">
                            <div class="col-md-6" v-for="course in courses.filter(e => e.program === 'Machine Operation')"
                                :key="course.id">
                                <CourseCard :course="course"></CourseCard>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 order-1 order-lg-2">
                        <h3 class="text-dark display-6 ">Machine Operator</h3>
                        <p class="lead">The globally skilled labor shortage is one of the greatest challenges facing the
                            construction industry.</p>
                        <p class="lead">With the rise in the construction of commercial mega structures like Lekki deep
                            Seaport, Dangote
                            refinery, and EKo –Atlantic projects, the skill, and competency –gap for heavy machine operators
                            became wider hence the increase in demand for the heavy machine operator in the labour market.
                            Our machinery operator training is both classroom training and hands-on-the-job learning under
                            the supervision of experienced instructors.
                        </p>
                    </div>
                </div>
            </div>
            <div class="mb-5">
                <div class="row gx-md-5 align-items-center">
                    <div class="col-lg-6">
                        <h3 class="text-dark display-6 "> SME's Digital Business Bootcamp</h3>
                        <p class="lead">These digital boot camp sessions will help small-scale business owners, storefront
                            operators, and self-employed to scale up their businesses with a virtual store via digital and
                            social media channels.</p>
                        <p class="lead">These channels will allow them to connect with clientele on the, go, thereby
                            increasing
                            patronage and income.
                        </p>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-md-6"
                                v-for="course in courses.filter(e => e.program === 'SME Digital Boot-camp')"
                                :key="course.id">
                                <CourseCard :course="course"></CourseCard>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
    height: 85vh;
    object-fit: cover;
}
</style>