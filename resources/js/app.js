// import './bootstrap';
import '../css/app.css';


import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
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
            .use( vue3GoogleLogin, {
                clientId: props.auth_config?.google_client_id
            } )
            .mount( el );
    },
    progress: {
        delay: 0,
        color: '#fab908',
        showSpinner: true,
    },
} );
