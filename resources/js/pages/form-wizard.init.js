/*
Template Name: Ubold - Responsive Bootstrap 5 Admin Dashboard
Author: CoderThemes
Website: https://coderthemes.com/
Contact: support@coderthemes.com
File: Form wizard init js
*/
import bootstrap from 'bootstrap/dist/js/bootstrap.min';

window.bootstrap = bootstrap;

import Wizard from "../plugin/wizard.js";

new Wizard('#horizontalwizard');

new Wizard('#verticalwizard');
