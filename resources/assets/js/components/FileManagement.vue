<template>
    <div>
        <div class="form-group col-md-12">
            <label for="logo" class="control-label">Attachments</label>
            <br><br>
             <div class="col-md-12">
                <input type="file" multiple="multiple" id="attachments" @change="uploadFieldChange">
                <hr>
                <div class="col-md-12">
                    <div class="attachment-holder animated fadeIn" v-cloak v-for="(attachment, index) in attachments"> 
                        <span class="label label-primary">{{ attachment.name + ' (' + Number((attachment.size / 1024 / 1024).toFixed(1)) + 'MB)'}}</span> 
                        <span class="" style="background: red; cursor: pointer;" @click="removeAttachment(attachment)"><button class="btn btn-xs btn-danger">Remove</button></span>
                    </div>
                </div>
             </div>
             <br><br>
             <button class="btn btn-primary" @click="submit">Upload</button>
        </div>
    </div>
</template>
<script>
    export default {

        props: [
            'settings'
        ],

        data() {

            return {

                // You can store all your files here
                attachments: [],

                // Each file will need to be sent as FormData element
                data: new FormData(),

                errors: {

                },

                percentCompleted: 0, // You can store upload progress 0-100 in value, and show it on the screen

            }

        },

        watch: {

        },

        computed: {


        },

        methods: {

            getAttachmentSize() {
                
                this.upload_size = 0; // Reset to beginningƒ

                this.attachments.map((item) => { this.upload_size += parseInt(item.size); });
                
                this.upload_size = Number((this.upload_size).toFixed(1));

                this.$forceUpdate();

            },

            prepareFields() {
                
                if (this.attachments.length > 0) {
                    for (var i = 0; i < this.attachments.length; i++) {
                        let attachment = this.attachments[i];
                        this.data.append('attachments[]', attachment);
                    }
                }

            },

            removeAttachment(attachment) {
                
                this.attachments.splice(this.attachments.indexOf(attachment), 1);
                
                this.getAttachmentSize();

            },

            // This function will be called every time you add a file
            uploadFieldChange(e) {

                var files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;

                for (var i = files.length - 1; i >= 0; i--) {
                    this.attachments.push(files[i]);
                }

                // Reset the form to avoid copying these files multiple times into this.attachments
                document.getElementById("attachments").value = [];
            },

            submit() {

                this.prepareFields();

                var config = {
                    headers: { 'Content-Type': 'multipart/form-data' } ,
                    onUploadProgress: function(progressEvent) {
                        this.percentCompleted = Math.round( (progressEvent.loaded * 100) / progressEvent.total );
                        this.$forceUpdate();
                    }.bind(this)
                };

                // Make HTTP request to store announcement
                axios.post(this.settings.file_management.upload_files, this.data, config)
                .then(function (response) {
                    console.log(response);
                    if (response.data.success) {
                        console.log('Successfull Upload');
                        toastr.success('Files Uploaded!', 'Success');
                        this.resetData();
                    } else {
                        console.log('Unsuccessful Upload');
                        this.errors = response.data.errors;
                    }
                }.bind(this)) // Make sure we bind Vue Component object to this funtion so we get a handle of it in order to call its other methods
                .catch(function (error) {
                    console.log(error);
                });

            },

            // We want to clear the FormData object on every upload so we can re-calculate new files again.
            // Keep in mind that we can delete files as well so in the future we will need to keep track of that as well
            resetData() {
                this.data = new FormData(); // Reset it completely
                this.attachments = [];
            },

            start() {
                console.log('Starting File Management Component');
            },

        },

        created() {
            this.start();
        }

    }
</script>