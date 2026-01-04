import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/config/default/bootstrap.scss',
                'resources/scss/config/default/app.scss',
                'resources/scss/icons.scss',
                'resources/sass/app.scss',
                'resources/css/custom.css',

                'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
                'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
                'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css',
                'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css',
                'node_modules/mohithg-switchery/dist/switchery.min.css',
                'node_modules/flatpickr/dist/flatpickr.min.css',
                'node_modules/select2/dist/css/select2.min.css',
                'node_modules/spectrum-colorpicker2/dist/spectrum.min.css',
                'node_modules/clockpicker/dist/bootstrap-clockpicker.min.css',
                'node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
                'node_modules/selectize/dist/css/selectize.bootstrap3.css',
                'node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css',
                'node_modules/multiselect/css/multi-select.css', 

                'resources/js/app.js',
                'resources/js/layout.js',
                'resources/js/custom.js',
                'resources/js/head.js',
                'resources/js/pages/datatables.init.js',
                'resources/js/pages/form-advanced.init.js',
                'resources/js/pages/auth.js',
                'resources/js/pages/form-pickers.init.js',
                'resources/js/pages/dashboard-1.init.js',
            ],
            refresh: true,
        }),
    ],
});