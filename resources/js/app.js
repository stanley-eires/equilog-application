import './bootstrap';
import '../css/app.css';
import 'bootstrap';


import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import VueApexCharts from "vue3-apexcharts";
import vue3GoogleLogin from 'vue3-google-login';

const appName = window.document.getElementsByTagName( 'title' )[ 0 ]?.innerText || 'Laravel';

createInertiaApp( {
    title: ( title ) => `${ title } - ${ appName }`,
    resolve: ( name ) => resolvePageComponent( `./Pages/${ name }.vue`, import.meta.glob( './Pages/**/*.vue' ) ),
    setup( { el, App, props, plugin } ) {
        Waves.init();
        return createApp( { render: () => h( App, props ) } )
            .use( plugin )
            .use( ZiggyVue, Ziggy )
            .use( VueApexCharts )
            .use( vue3GoogleLogin, {
                clientId: '888479091012-0sfqr1n21hq3odmu1t277i4v349ghm8b.apps.googleusercontent.com'
            } )
            .mount( el );
    },
    progress: {
        color: '#4B5563',
        showSpinner: true,
    },
} );
