<script setup>
import Index from '@/Pages/Admin/User/Index.vue';
import { Link } from '@inertiajs/vue3';
import moment from 'moment';
import { ref } from 'vue';



defineProps( { title: String, user: Object } );
let currentTab = ref( 'medicals' );

let properDates = ( date ) => {
    let [ prefix, suffix ] = date ? date.split( ' - ' ).map( e => e.trim() ) : [];
    if ( prefix ) {
        return `${ moment( prefix ).format( 'MMMM, YYYY' ) } - ${ suffix.toLowerCase() === "current" ? 'Current' : moment( suffix ).format( 'MMMM, YYYY' ) }`;
    }
}

</script>
<template>
    <Index :user="user" :title="title" :breadcrumbs="true">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-body">
                    <h4>Profile</h4>
                    <div class="mb-5">{{ user.summary }}</div>
                    <h4>Recent Work Experience</h4>
                    <div class="row row-cols-md-2 mb-5">
                        <div>
                            <p>{{ properDates(user.work_experience.date) }}<br>
                                <span class="text-muted">{{ user.work_experience.location }}</span>
                            </p>
                        </div>
                        <div>
                            <p>
                                <span
                                    class="fw-bold  text-uppercase">{{ user.work_experience.title }}</span><br>{{ user.work_experience.employer }}
                            </p>
                        </div>
                    </div>
                    <h4>Highest Education Background</h4>
                    <div class="row row-cols-md-2 mb-5">
                        <div>
                            <p>{{ properDates(user.education.date) }}<br>
                                <span class="text-muted"> {{ user.education.location }}</span>
                            </p>
                        </div>
                        <div>
                            <p>
                                <span
                                    class="fw-bold  text-uppercase">{{ user.education.institution }}</span><br>{{ user.education.degree }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-body">
                    <ul class="nav nav-tabs  text-uppercase mb-3 small">
                        <li class="nav-item">
                            <button class="nav-link bg-transparent" :class="{ 'active': currentTab == 'medicals' }"
                                @click.prevent="currentTab = 'medicals'">Medicals</button>
                        </li>
                        <li class="nav-item">
                            <button @click.prevent="currentTab = 'kin'" class="nav-link bg-transparent"
                                :class="{ 'active': currentTab != 'medicals' }" href="#">Next
                                Of Kin</button>
                        </li>
                    </ul>
                    <div v-if="currentTab == 'medicals'">
                        <table class='table table-sm table-centered'>
                            <tbody>
                                <tr>
                                    <th>Genotype</th>
                                    <td>{{ user.medicals.genotype }}</td>
                                </tr>
                                <tr>
                                    <th>Blood Group</th>
                                    <td>{{ user.medicals.bloodgroup }}<small class="ms-1">({{ user.medicals.rhd }})</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Height</th>
                                    <td>{{ user.medicals.height }}</td>
                                </tr>
                                <tr>
                                    <th>Weight</th>
                                    <td>{{ user.medicals.weight }}</td>
                                </tr>
                                <tr>
                                    <th>Eye Sight</th>
                                    <td>{{ user.medicals.vision }}</td>
                                </tr>
                                <tr>
                                    <th>Hearing Clear</th>
                                    <td>{{ user.medicals.hearing }}</td>
                                </tr>
                                <tr>
                                    <th>HIV I&II </th>
                                    <td>{{ user.medicals.hiv }}</td>
                                </tr>
                                <tr>
                                    <th>COVID 19</th>
                                    <td>{{ user.medicals.covid }}</td>
                                </tr>
                                <tr>
                                    <th>Cerebrospinal meningitis</th>
                                    <td>{{ user.medicals.meningitis }}</td>
                                </tr>
                                <tr>
                                    <th>Serum Tuberculosis</th>
                                    <td>{{ user.medicals.tuberculosis }}</td>
                                </tr>

                                <tr>
                                    <th>Allergies</th>
                                    <td>None</td>
                                </tr>
                            </tbody>
                        </table>
                        <a target="_blank" :href="`/storage/${user.medicals.certificate}`" v-if="user.medicals.certificate"
                            class="btn btn-dark">View Medical Report</a>
                    </div>
                    <div v-else>
                        <table class='table table-centered'>
                            <tbody>
                                <tr>
                                    <th>Fullname</th>
                                    <td>{{ user.next_of_kin.name }}</td>
                                </tr>
                                <tr>
                                    <th>Relationship</th>
                                    <td>{{ user.next_of_kin.relationship }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ user.next_of_kin.email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone Number</th>
                                    <td>{{ user.next_of_kin.phone }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </Index>
</template>