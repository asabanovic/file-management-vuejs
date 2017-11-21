
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

window.Event = new class {
 	constructor () {
 		this.vue = new Vue();
 	} 

 	fire(event, data = null) {
 		this.vue.$emit(event, data);
 	} 

 	listen(event, callback) {
 		this.vue.$on(event, callback);
 	}
 }

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('file-management', require('./components/FileManagement.vue'));
Vue.component('attachment-list', require('./components/AttachmentList.vue'));

const app = new Vue({
	el: '#app',

	data: {

		loading: false,

		percent: null,

	},
    
    methods: {
    	
    },

    created() {

		console.log('App created');

		Event.listen('percent', function(percent) {
			console.log('Received Upload Percent Status!');
			this.percent = percent;
		}.bind(this));

		Event.listen('loading_on', function() {
			console.log('Received Loading ON Event!');
			this.loading = true;
		}.bind(this));

		Event.listen('loading_off', function() {
			console.log('Received Loading OFF Event!');
			this.loading = false;
		}.bind(this));

	}
});
